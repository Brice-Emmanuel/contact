<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Règle de sécurité réutilisable pour les mots de passe
     */
    private function passwordRules(): array
    {
        return [
            'required',
            'string',
            'confirmed',
            PasswordRule::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
        ];
    }

    /**
     * Messages d'erreur personnalisés pour la validation
     */
    private function customValidationMessages(): array
    {
        return [
            'email.required'     => 'L\'adresse email est obligatoire.',
            'email.email'        => 'Veuillez fournir une adresse email valide.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les deux mots de passe ne correspondent pas.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un symbole.',
        ];
    }

    // ------------------------------------------------------------------------
    // API ENDPOINTS
    // ------------------------------------------------------------------------

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => $this->passwordRules(),
        ], $this->customValidationMessages());

        $user = User::create([
            'name'          => $fields['name'],
            'email'         => $fields['email'],
            'password'      => Hash::make($fields['password']),
            'plan'          => 'free',
            'contact_limit' => 100,
        ]);

        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès.',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string',
        ], $this->customValidationMessages());

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Identifiants incorrects.'
            ], 401);
        }

        $token = $user->createToken('token_user')->plainTextToken;

        return response()->json([
            'status' => 'Connexion Réussie',
            'data'   => [
                'user'  => $user,
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Déconnexion réussie et token supprimé avec succès.'
        ]);
    }

    // ------------------------------------------------------------------------
    // WEB ENDPOINTS
    // ------------------------------------------------------------------------

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('info', 'Vous êtes déjà connecté.');
        }
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('info', 'Vous êtes déjà connecté.');
        }
        return view('auth.register');
    }

    public function loginWeb(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], $this->customValidationMessages());

        $credentials = $request->only('email', 'password');
        $remember    = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Bienvenue ' . Auth::user()->name . ' !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants sont incorrects.',
        ])->withInput($request->only('email'));
    }

    public function registerWeb(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => $this->passwordRules(),
        ], $this->customValidationMessages());

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'plan'          => 'free',
            'contact_limit' => 100,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // ✅ CODE CORRIGÉ :
return redirect()->route('dashboard')
    ->with('success', 'Inscription réussie ! Bienvenue sur votre tableau de bord. 🎉');
    }

    public function logoutWeb(Request $request)
    {
        $userName = Auth::user()->name ?? 'Utilisateur';

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'À bientôt ' . $userName . ' !');
    }

    // ------------------------------------------------------------------------
    // EMAIL VERIFICATION
    // ------------------------------------------------------------------------

    public function showVerificationNotice()
    {
        if (Auth::check() && Auth::user()->email_verified_at !== null) {
            return redirect()->route('dashboard')
                ->with('info', 'Votre email est déjà vérifié.');
        }

        return view('auth.verify-email');
    }

    public function resendVerificationWeb(Request $request)
    {
        if ($request->user() && $request->user()->email_verified_at !== null) {
            return redirect()->route('dashboard')
                ->with('info', 'Votre email est déjà vérifié.');
        }

        if (method_exists($request->user(), 'sendEmailVerificationNotification')) {
            $request->user()->sendEmailVerificationNotification();
        }

        return back()->with('success', 'Un nouveau lien de vérification vous a été envoyé. 📧');
    }

    public function verifyEmailWeb(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        $userEmail = method_exists($user, 'getEmailForVerification') 
            ? $user->getEmailForVerification() 
            : $user->email;

        if (!hash_equals((string) $hash, sha1($userEmail))) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Lien de vérification invalide.']);
        }

        if ($user->email_verified_at !== null) {
            if (Auth::check()) {
                return redirect()->route('dashboard')
                    ->with('info', 'Email déjà vérifié.');
            }
            return redirect()->route('login')
                ->with('success', 'Email déjà vérifié. Vous pouvez vous connecter.');
        }

        if (method_exists($user, 'markEmailAsVerified')) {
            $user->markEmailAsVerified();
        } else {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Email vérifié avec succès ! Bienvenue ' . $user->name . ' !');
    }

    // ------------------------------------------------------------------------
    // PASSWORD RESET
    // ------------------------------------------------------------------------

    public function showForgotForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('info', 'Vous êtes déjà connecté.');
        }

        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(
            ['email' => 'required|email'],
            $this->customValidationMessages()
        );

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Un lien de réinitialisation vous a été envoyé par email. 📧');
        }

        return back()->withErrors(['email' => 'Aucun utilisateur trouvé avec cette adresse email.']);
    }

    public function showResetForm(Request $request, $token = null)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('info', 'Vous êtes déjà connecté.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => $this->passwordRules(),
        ], $this->customValidationMessages());

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Mot de passe réinitialisé avec succès ! Vous pouvez maintenant vous connecter.');
        }

        return back()->withErrors(['email' => 'Une erreur est survenue lors de la réinitialisation du mot de passe.']);
    }
}