<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix SSL certificate verification for SMTP
        $this->app->resolving(MailManager::class, function ($mailManager) {
            $mailManager->extend('smtp', function ($config) {
                // Build DSN string
                $encryption = $config['encryption'] ?? 'tls';
                $host = $config['host'] ?? '127.0.0.1';
                $port = $config['port'] ?? 2525;
                $username = $config['username'] ?? null;
                $password = $config['password'] ?? null;
                
                // Create DSN
                $dsn = sprintf(
                    'smtp://%s:%s@%s:%d',
                    urlencode($username ?? ''),
                    urlencode($password ?? ''),
                    $host,
                    $port
                );
                
                if ($encryption) {
                    $dsn .= '?encryption=' . $encryption;
                }
                
                // Create transport from DSN
                $transport = Transport::fromDsn($dsn);
                
                // Disable SSL verification for certificate mismatch
                if ($transport instanceof SmtpTransport) {
                    $stream = $transport->getStream();
                    
                    if ($stream instanceof SocketStream) {
                        // Get existing options and merge with SSL options
                        $existingOptions = $stream->getStreamOptions();
                        $stream->setStreamOptions(array_merge($existingOptions, [
                            'ssl' => [
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true,
                            ],
                        ]));
                    }
                }
                
                // Return the transport directly - Laravel will handle it
                return $transport;
            });
        });
    }
}
