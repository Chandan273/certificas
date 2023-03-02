<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title  >Certificate</title>
    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
            border: 1px solid #ddd;
            font-family: "Poppins", "Open Sans", sans-serif;
            padding: 16px
        }
        table.subject-table, .subject-table th, .subject-table td {
            border: 1px solid black;
            border-collapse: collapse;
        }   
    </style>
</head>

<body  style="background-color:#f1f3f4;" >
    <table width="100%" cellspacing="2" cellpadding="2">
        <tr>
            <td colspan="2">
            <p style="text-decoration: underline;" ><b> Date</b> <span> 24/02/2023 </span>  </p>
            </td>
        </tr>
        <tr style="vertical-align:top">            
            <td width="100%" colspan="2" rowspan="1">
                <table width="100%">
                    <td width="80%">
                        <h2
                            style="font-family: Arial, sans-serif, 'Open Sans';margin: 0px 0px 10px; line-height: 1.6rem;text-align:left;text-transform: uppercase;color:#008cff">
                           Certificate</h2>
                        <h3
                            style="font-family: Arial, sans-serif, 'Open Sans';margin: 10px 0px; line-height: 1.6rem;text-align:left;">
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
                                <b>Student Name:</b><span style="border-bottom: 1px dotted #000;" >{{ $name }}</span> 
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p
                                style="font-family: Arial, sans-serif, 'Open Sans';font-size: 0.875rem; margin: 0; line-height: 1.6rem;">
                                <b>Student Email:</b>
                                <span style="border-bottom: 1px dotted #000;" >  {{ $email }}</span>
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
                                <b>Student DOB:</b>   <span style="border-bottom: 1px dotted #000;" >{{ $birth_date }}</span>

                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p
                                style="font-family: Arial, sans-serif, 'Open Sans';font-size: 0.875rem; margin: 0; line-height: 1.6rem;">
                                <b>Student Birth Place:</b> <span style="border-bottom: 1px dotted #000;" >{{ $birth_place }}</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="margin:10px auto 20px;;width:100%;" class="subject-table">
        <tr>
            <th style="text-align:center;">Sr. No</th>
            <th style="text-align:center;">Subject Name</th>
            <th style="text-align:center;">Obtained Grades</th>
            <th style="text-align:center;">Exam Status</th>
            
        </tr>
        <tr>
            <td style="text-align:center;">1.</td>
            <td style="text-align:center;">English</td>
            <td style="text-align:center;">C</td>
            <td style="text-align:center;">Good</td>
            
        </tr>
        <tr>
            <td style="text-align:center;">2.</td>
            <td style="text-align:center;">Math</td>
            <td style="text-align:center;">C</td>
            <td style="text-align:center;">Good</td>
        </tr>
        <tr>
            <td style="text-align:center;">3.</td>
            <td style="text-align:center;">I.T</td>
            <td style="text-align:center;">A</td>
            <td style="text-align:center;">Excellent</td>
        </tr>
        <tr>
            <td style="text-align:center;">4.</td>
            <td style="text-align:center;">Chemistry</td>
            <td style="text-align:center;">B</td>
            <td style="text-align:center;">V Good</td>
        </tr>
        <tr>
            <td style="text-align:center;">5.</td>
            <td style="text-align:center;">Physics</td>
            <td style="text-align:center;">B</td>
            <td style="text-align:center;">V Good</td>
        </tr>
        <tr>
            <td style="text-align:center;"></td>
            <td style="text-align:center;"><b>Over All Performance</b></td>
            <td style="text-align:center;"> <b>B</b></td>
            <td style="text-align:center;"></td>
        </tr>
    </table>

 <table style="width:100%">
   <tr>
     <td> <p><B>Issue Date </B> <span > 24 February 2022 </span></p></td>
     <td>
     <h4 style="float:right;"> Signature </h4>
     </td>
     
   </tr>

 </table>
    <p><b>Note :</b>industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.<br><br> <i> A  stands for “Excellent” performance in all the areas<br>
B - Very good ( 85 and above )<br>
C - Good (70 and above)<br>
D - Fairly good or Satisfactory (60 and above)    <br> 
E - Sufficient (45 and above ) <br>
F - Fail (below 40 "performance that does not meet the minimum academic criteria." ) </i> </p>
</body>
</html>