<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
$appName = Html::encode(Yii::$app->name);
$username = Html::encode($user->username);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelszó visszaállítás - <?= $appName ?></title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f6f9fc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f6f9fc;">
        <tr>
            <td style="padding: 40px 20px;">
                
                <!-- Main Email Content -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 0 40px; text-align: center;">
                            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 24px; display: flex; align-items: center; justify-content: center;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <circle cx="12" cy="16" r="1"></circle>
                                    <path d="m7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <h1 style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #1a202c; line-height: 1.2;">
                                Jelszó visszaállítás
                            </h1>
                            <p style="margin: 0; font-size: 16px; color: #718096; line-height: 1.5;">
                                Biztonságos újrakezdés egy kattintással
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <p style="margin: 0 0 24px; font-size: 16px; color: #2d3748; line-height: 1.6;">
                                <strong>Kedves <?= $username ?>!</strong>
                            </p>
                            
                            <p style="margin: 0 0 32px; font-size: 16px; color: #4a5568; line-height: 1.6;">
                                Jelszó visszaállítási kérést kaptunk a fiókjához. Ha te voltál, kattints az alábbi gombra egy új jelszó beállításához:
                            </p>
                            
                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 40px 0;">
                                <a href="<?= $resetLink ?>" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 8px; font-weight: 600; font-size: 16px; line-height: 1; box-shadow: 0 4px 14px rgba(102, 126, 234, 0.4); transition: all 0.2s ease;">
                                    🔑 Jelszó visszaállítása
                                </a>
                            </div>
                            
                            <!-- Security Info -->
                            <div style="background: linear-gradient(135deg, #fef5e7 0%, #f7fafc 100%); border-left: 4px solid #f6ad55; border-radius: 8px; padding: 24px; margin: 32px 0;">
                                <div style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                                    <span style="font-size: 20px; margin-right: 12px;">🛡️</span>
                                    <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #2d3748;">
                                        Biztonsági információk
                                    </h3>
                                </div>
                                <ul style="margin: 0; padding-left: 32px; color: #4a5568; line-height: 1.8;">
                                    <li style="margin-bottom: 8px;">⏰ Ez a link <strong>1 órán belül lejár</strong></li>
                                    <li style="margin-bottom: 8px;">🔒 A link <strong>csak egyszer használható</strong></li>
                                    <li>❌ Ha nem te kérted, <strong>figyelmen kívül hagyhatod</strong> ezt az emailt</li>
                                </ul>
                            </div>
                            
                        </td>
                    </tr>
                    
                    <!-- Alternative Link -->
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #f7fafc; border-radius: 8px; padding: 20px;">
                                <p style="margin: 0 0 12px; font-size: 14px; color: #718096; font-weight: 500;">
                                    Ha a gomb nem működik, másold be ezt a linket a böngésződbe:
                                </p>
                                <p style="margin: 0; word-break: break-all; font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; font-size: 12px; color: #4a5568; background-color: #edf2f7; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    <?= Html::encode($resetLink) ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 32px 40px 40px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 16px; font-size: 14px; color: #718096; line-height: 1.6;">
                                Ha kérdésed van, vagy nem te kérted ezt a jelszó visszaállítást,<br>
                                kérjük, vedd fel velünk a kapcsolatot.
                            </p>
                            <div style="margin: 24px 0;">
                                <p style="margin: 0; font-size: 16px; color: #2d3748; font-weight: 500;">
                                    Üdvözlettel,
                                </p>
                                <p style="margin: 8px 0 0; font-size: 18px; color: #667eea; font-weight: 700;">
                                    <?= $appName ?> csapata
                                </p>
                            </div>
                        </td>
                    </tr>
                    
                </table>
                
                <!-- Email Footer -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="max-width: 600px; margin: 24px auto 0;">
                    <tr>
                        <td style="text-align: center; padding: 0 20px;">
                            <p style="margin: 0; font-size: 12px; color: #a0aec0; line-height: 1.5;">
                                Ez egy automatikus email. Kérjük, ne válaszolj erre az üzenetre.<br>
                                © <?= date('Y') ?> <?= $appName ?>. Minden jog fenntartva.
                            </p>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
