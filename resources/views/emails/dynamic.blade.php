<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body style="margin:0; padding:0; background:#f3f4f6; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:12px; overflow:hidden;">

                    <tr>
                        <td style="background:#10b981; padding:20px; text-align:center; color:white;">
                            <h2 style="margin:0;">
                                {{ setting('store.name') }}
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; color:#374151; font-size:14px; line-height:1.6;">

                            {!! nl2br(e($content)) !!}

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:20px;">
                            <a href="#"
                                style="background:#10b981; color:white; padding:12px 20px; text-decoration:none; border-radius:8px; font-size:14px;">
                                Kunjungi Toko
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#9ca3af;">
                            © {{ date('Y') }} {{ setting('store.name') }} <br>
                            Semua hak dilindungi
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
