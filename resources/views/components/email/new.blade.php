<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style>
        /* Reset styles go here */
        body {
            margin: 0;
            padding: 0;
            min-width: 100%;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        .webkit {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body style="background-color: #f4f4f7; color: #51545E;">
<center class="wrapper" style="width: 80%; table-layout: fixed; background-color: #f4f4f7; padding-bottom: 40px;">
    <div class="webkit">
        <table class="outer" align="center"
               style="border-spacing: 0; font-family: sans-serif; color: #333333; margin: 0 auto; width: 100%; max-width: 600px;">
            <tr>
                <td style="padding: 20px;">
                    {{ $slot }}
                </td>
            </tr>
        </table>
    </div>
</center>
</body>
</html>
