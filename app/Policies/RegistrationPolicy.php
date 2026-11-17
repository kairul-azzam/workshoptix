<?php

namespace App\Policies;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Aturan akses untuk pendaftaran (dan tiketnya).
 * Peserta hanya boleh membuka pendaftaran miliknya sendiri.
 *
 * Laravel menemukan policy ini otomatis karena namanya
 * RegistrationPolicy untuk model Registration.
 */
class RegistrationPolicy
{
    public function view(User $user, Registration $registration): Response
    {
        return $user->id === $registration->user_id
            ? Response::allow()
            : Response::deny('Pendaftaran ini bukan milikmu.');
    }
}