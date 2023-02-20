<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
            border: 1px solid #ddd;
            padding: 16px
        }
    </style>
</head>

<body>
    <table width="100%" cellspacing="2" cellpadding="2">
        <tr style="vertical-align:top">
            <td width="100%" colspan="2" rowspan="1">
                <table width="100%">
                    <td width="80%">
                        <h2
                            style="font-family: Arial, sans-serif, 'Open Sans';margin: 0px 0px 10px; line-height: 1.6rem;text-align: center;text-transform: uppercase;">
                            Eagle Mountain School of Fine Arts</h2>
                        <h3
                            style="font-family: Arial, sans-serif, 'Open Sans';margin: 10px 0px; line-height: 1.6rem;text-align: center;">
                            Academic Session - Aug 2023</h3>
                    </td>
                    <td width="20%" style="vertical-align:top;text-align: right;">
                        <img src="{{ $qrCodePath }}" alt="QR Code" width="100px" />
                    </td>
                </table>
            </td>
        </tr>
        <tr style="vertical-align:top;">
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td>
                            <p
                                style="font-family: Arial, sans-serif, 'Open Sans';font-size: 0.875rem; margin: 0; line-height: 1.6rem;">
                                <b>Student Name:</b>{{ $name }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p
                                style="font-family: Arial, sans-serif, 'Open Sans';font-size: 0.875rem; margin: 0; line-height: 1.6rem;">
                                <b>Student Email:</b> {{ $email }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td>
                            <p
                                style="font-family: Arial, sans-serif, 'Open Sans';font-size: 0.875rem; margin: 0; line-height: 1.6rem;">
                                <b>Student DOB:</b> {{ $birth_date }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p
                                style="font-family: Arial, sans-serif, 'Open Sans';font-size: 0.875rem; margin: 0; line-height: 1.6rem;">
                                <b>Student Birth Place:</b> {{ $birth_place }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
