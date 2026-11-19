<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('wso2:link-user {email : Existing Inventra email} {subject : Exact WSO2 OIDC subject identifier}')]
#[Description('Allow an existing Inventra user to sign in with a specific WSO2 identity')]
class LinkWso2User extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $issuer = config('wso2.issuer');
        $subject = $this->argument('subject');
        if (! is_string($issuer) || ! str_starts_with($issuer, 'https://') || strlen($issuer) > 255
            || trim($subject) === '' || strlen($subject) > 255) {
            $this->error('Configure WSO2_ISSUER and supply a valid exact subject.');

            return self::FAILURE;
        }
        $user = User::where('email', $this->argument('email'))->first();
        if (! $user) {
            $this->error('The Inventra account does not exist.');

            return self::FAILURE;
        }
        if ($user->wso2_subject && ($user->wso2_subject !== $subject || $user->wso2_issuer !== $issuer)) {
            $this->error('This user already has a different WSO2 identity.');

            return self::FAILURE;
        }
        if (User::where('wso2_issuer', $issuer)->where('wso2_subject', $subject)->where('id', '!=', $user->id)->exists()) {
            $this->error('That WSO2 identity is already linked to another user.');

            return self::FAILURE;
        }
        $user->wso2_issuer = $issuer;
        $user->wso2_subject = $subject;
        $user->save();
        $this->info('WSO2 identity linked to the existing Inventra account.');

        return self::SUCCESS;
    }
}
