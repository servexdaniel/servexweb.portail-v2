<?php

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use App\Models\Contact;

use function Livewire\Volt\state;

state([
    'CcName' => fn() => auth('contact')->user()->CcName,
    'email' => fn() => auth('contact')->user()->email,
]);

$updateProfileInformation = function () {
    $contact = Auth::guard('contact')->user();

    $validated = $this->validate([
        'CcName' => ['required', 'string', 'max:255'],
        //'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Contact::class)->ignore($contact->id)],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
    ]);

    $contact->fill($validated);

    /*
     * If the contact's email address has been changed we need to
     * reset the email verification status.
     *
     *
    if ($contact->isDirty('email')) {
        $contact->email_verified_at = null;
    }
    */

    $contact->save();

    $this->dispatch('profile-updated', name: $contact->CcName);
};

$sendVerification = function () {
    $user = Auth::guard('contact')->user();
    if ($user->hasVerifiedEmail()) {
        $this->redirectIntended(default: route('contact.dashboard', ['language' => app()->getLocale()], absolute: false));

        return;
    }

    $user->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="CcName" :value="__('Name')" />
            <x-text-input wire:model="CcName" id="CcName" name="CcName" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('CcName')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
