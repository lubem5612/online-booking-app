<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta property="og:title" content="Online Booking Default Email">
    <title>Online Booking</title>
    <style type="text/css">

        body {
            width: 100% !important;
            -webkit-text-size-adjust: none;
            margin: 0;
            padding: 0;
        }

        img {
            border: none;
            font-size: 14px;
            font-weight: bold;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            text-transform: capitalize;
        }

        #backgroundTable {
            height: 100% !important;
            margin: 0;
            padding: 10px;
            width: 100% !important;
        }

        body,
        .backgroundTable {
            background-color: rgba(235, 235, 235, 0.59);
        }

        #templateContainer {
            background: white;
        }

        #headerImage {
            height: auto;
            max-width: 600px !important;
        }

        .bodyContent {
            background-color: #fff;
        }

        .bodyContent .bodyContentArea {
            margin: 50px 20px 0px 20px;
            padding-bottom: 30px;
            color: #000;
            font-family: Open Sans, Arial;
            font-size: 12px;
            line-height: 150%;
            text-align: left;
            border-bottom: 1px solid #EBEBEB;
        }

        .bodyContent .bodyContentArea a:link,
        .bodyContent .bodyContentArea a:visited {
            color: #00A1C0;
            font-weight: normal;
            text-decoration: underline;
        }

        .bodyContent img {
            display: inline;
            margin-bottom: 10px;
        }

        .bodyContent li {
            color: #00A1C0;
            padding-bottom: 5px;
            padding-top: 5px;
        }

        .bodyContent li span {
            color: #222222;
        }

        .bodyContent .btn {
            border: 1px solid #00A1C0;
            padding: 10px;
            text-align: center;
        }

        .bodyContent .bodyContentArea .btn a:link,
        .bodyContent .bodyContentArea .btn a:visited {
            text-decoration: none;
        }

        .bodyContent .btn span {
            color: #00A1C0;
            text-transform: uppercase;
            margin: 0 20px;
            font-size: 14px;
            font-family: Open Sans, Arial;
        }

        .bodyContent .btnOuterDark .btn {
            background: #00A1C0;
        }

        .bodyContent .btnOuterDark .btn span {
            color: white;
        }

        #templateFooter {
            border-top: 0;
        }

        .footerContent div {
            color: rgba(235, 235, 235, 0.59);
            font-family: Open Sans, Arial;
            font-size: 12px;
            line-height: 125%;
            text-align: left;
        }

        .footerContent div a:link,
        .footerContent div a:visited {
            color: #00A1C0;
            font-weight: normal;
            text-decoration: underline;
        }

        .footerContent img {
            display: inline;
        }

        #learnMore {
            background-color: #fff;
            color: #000;
        }

        #learnMore div {
            color: #000;
            font-size: 10px;
            line-height: 165%;
            text-align: center;
        }

        #social {
            background-color: #fff;
            padding: 0 0 20px 0;
        }

        #social img {
            padding: 0 4px;
        }

        .bodyContent .bodyContentArea .usefullLinks {
            margin: 0 auto;
            text-align: center;
            width: 100%;
        }

        .bodyContent .bodyContentArea .usefullLinks a:link,
        .bodyContent .bodyContentArea .usefullLinks a:visited {
            text-decoration: none;
        }

        @media only screen and (min-device-width: 480px) {
            #learnMore div {
                padding: 0 30px;
            }

        }
    </style>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-text-size-adjust: none;margin: 0;padding: 0;background-color: rgba(235,235,235,0.59);width: 100% !important;">
<center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 10px;height: 100% !important;width: 100% !important;">
        <tr>
            <td align="center" valign="top">
                <table border="0" cellpadding="0" cellspacing="0" style="max-width: 600px;background: white;" id="templateContainer">
                    <tr>
                        <td align="center" valign="top">
                            <img align="none" src="{{asset('images/logos/logo.png')}}" style="width: auto; height: 35px;">
                            <hr style="border: 1px solid #E4E4E4; width: 70%">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" style="max-width:520px;" id="templateBody">
                                <tr>
                                    <td valign="top" class="bodyContent" style="background-color: #fff;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top">
                                                    <div class="bodyContentArea" style="color: #455065; margin: 0px 20px;padding-bottom: 30px;text-align: center;">
                                                        <p style="font-size: 14px"><span>Hello</span> <span style="font-weight: bold;">{{$data['admin']}}</span></p>
                                                        <br>
                                                        <p style="font-size: 14px">The following have defaulted on your booking platform</p>
                                                        <br>

                                                        <p style="font-weight: bold; text-align: left; margin-left: 35%"><span style="color: #3DB166;">Name -</span> <span style="color: #242424;">{{$data['name']}}</span></p>
                                                        <br>
                                                        <p style="font-weight: bold; text-align: left; margin-left: 35%"><span style="color: #3DB166;">Email -</span> <span style="color: #242424;">{{$data['email']}}</span></p>
                                                        <br>
                                                        <p style="font-weight: bold; text-align: left; margin-left: 35%"><span style="color: #3DB166;">Due date -</span> <span style="color: #242424;">{{$data['date']}}</span></p>
                                                        <br>

                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
            </td>
        </tr>
    </table>
</center>
</body>

</html>


