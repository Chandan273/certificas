<div class="content email_temp" style="margin: 0px auto; font-family: Roboto,Helvetica,Arial,sans-serif;background:#eeeee7;padding:9px;width:600px;margin:0 auto;
         border: 1px solid #e4e4e4;">
    <div class="container">
        <!-- <div class="header_mail" style="border-bottom: 0;padding-bottom: 6px;text-align: center;margin-bottom: 9px;">
            <div class="container"><img src="{{asset('assets/images/tixou-new-logo.png')}}" width="110px" /></div>
        </div> -->
        <div class="email_body"
            style="border: 1px solid #e3e3e3;background-color: #fff;padding: 20px;border-radius: 5px;">
            <div class="row">
                <div class="col-md-12">
                    <h3 style=" margin: 0px 0 8px;font-size: 16px;font-weight: 500;color: #757575;;margin-top: 17px;">Hi
                        {{ $name }},</h3>
                    <p
                        style="font-size: 16px;font-family: Roboto,Helvetica,Arial,sans-serif;color: #72807b;line-height: 24px;margin-bottom: 24px;">
                        A request has been received to change the password of your certificas account.</p>
                        <div style="width:100%;text-align:center"> 
                    <a href={{ $resetPassword }} style="padding: 10px;text-decoration: none;color: #fff;
                    background-color: #000000;
                    border-color: #000000;
                    border-radius: 4px;
                    ">Reset Password</a>
                    </div>

                    <p
                        style="color: #72807b;
                        line-height: 24px;
                        font-size: 16px;
                        margin-top: 22px;
                        font-family: Roboto,Helvetica,Arial,sans-serif;">
                        If you didn't initiate this request, please feel free to get in touch with us at
                        <mailto:i><strong>support@certificas.com</strong></i>
                    </p>
                    <h3 style="margin: 18px 0px 8px 0; font-size: 16px;color: #525050;">Thanks &amp; Regards,</h3>
                    <p style="font-size: 14px; margin: 0; color: #5a5656;">Certificas Team</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div
                        style="padding-top:15px;padding-right:0;padding-bottom:0;padding-left:0;margin-top:16px;color:rgba(0,0,0,0.68);font-size:12px;text-align:center;border-top:1px solid #dedede">
                        Sent by <a href="#" style="color:#6e5ba4;text-decoration:underline;white-space:nowrap"
                            target="_blank">Certificas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>