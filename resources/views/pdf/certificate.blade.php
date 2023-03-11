<!doctype html><html class="no-js" lang="nl-NL"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="W. Diepeveen">
    <meta name="keywords" content="">
    <title>Certificaat</title>
    <style>
    * {
        font-family: "DejaVu Sans", sans-serif;
        padding: 0;
        margin: 0;
    }
    body {
        font-size: 12pt;
    }
    table {
        border-collapse: collapse;
    }
    h1 {
        font-size: 14pt;
        font-weight: normal;
    }
    h2 {
        font-size: 13pt;
        font-weight: normal;
    }
    h3 {
        font-size: 11pt;
        text-decoration: underline;
    }
    a {
        color: black;
        text-decoration: none;
    }
    
    .alignright {
        text-align: right;
    }
    .page, #background {
        position: absolute;
        top: 0;
        left: 0;
    }
    .page {
        z-order: 2;
    }
    #background {
        z-order: 1;
    }
    
    .info {
        position: absolute;
        display: none;
    }
    
    #label_uitgereikt_aan {
        display: block;
        top: 510px;
        left: 220px;
        width:400px;
    }
    #label_geboortedatum {
        display: block;
        top: 550px;
        left: 220px;
         width:400px;
    }
    #label_datum_toetsing {
        display: block;
        top: 590px;
        left: 220px;
         width:400px;
    }
    #label_geldig_tot {
        display: block;
        top: 630px;
        left: 220px;
         width:400px;
    }
    #label_company {
        display: block;
        top: 800px;
        left: 220px;
        width:300px;
    }
    #label_naam {
        display: block;
        top: 900px;
        left: 220px;
        width:300px
    }
    
    #p_naam {
        display: block;
        top: 510px;
        left: 400px;
    }
    #p_geboortedatum {
        display: block;
        top: 550px;
        left: 400px;
        width:300px;
    }
    #d_toetsingsdatum {
        display: block;
        top: 590px;
        left: 400px;
         width:300px;
    }
    #d_geldig_tot {
        display: block;
        top: 630px;
        left: 400px;
         width:300px;
    }
    
    #footer_left {
        display: block;
        top: 1040px;
        left: 50px;
        line-height: 90%;
        width:300px;
    }
    #footer_right {
        display: block;
        top: 1040px;
        left: 500px;
        line-height: 90%;
    }
    #certificaathouder {
        display: block;
        top: 900px;
        left: 500px;
    }
    #certificate_url {
    color: rgb(255, 181, 158);
        display: block;
        top: 455px;
        left: -200px;
        font-size: 10px;
        width: 700px;
        transform: rotate(270deg);
    }
    #certificate_created{
            color: rgb(255, 181, 158);
        display: block;
        top: 455px;
        left: -188px;
        font-size: 9px;
        width: 700px;
        transform: rotate(270deg);
    }
    #certificate_expired {
        color: rgb(240, 240, 240);
        opacity: 0.5;
        display: block;
        top: 440px;
        left: -71px;
        font-size: 220px;
        transform: rotate(310deg);
        text-transform: uppercase;
    }
    
    #c_titel {
        display: block;
        top: 270px;
        left: 220px;
        font-size: 24pt;
        line-height: 0.8em;
        width:500px;
    }
    #c_subtitel {
        display: block;
        top: 320px;
        left: 220px;
        font-size: 17pt;
        line-height: 1em;
        width:500px;
    }
    img#QRcode {
        position: absolute;
        display: block;
        top: 830px;
        left: 650px;
        font-size: 18px;
        line-height: 0.8em;
        width: 70px;
    }
    </style>
    </head><body>
    <img id="background" src="https://certificate.psalltrain.nl/includes/templates/ps-alltrain/certificate-front.jpg" width="100%" />
    <img id="QRcode" src="{{ $qrCodePath }}" alt="QR Code" />
    <div class="page">
    
    <?php
    $data=array();
    $data['p_naam'] = $name;
    $data['p_geboortedatum'] = $birth_date;
    $data['d_toetsingsdatum'] = $valid_from;
    $data['d_geldig_tot'] = $valid_untill;
    $setup = array(
        'label_uitgereikt_aan' => 'Uitgereikt aan',
        'label_datum_toetsing' => 'Datum toetsing',
        'label_geldig_tot' => 'Geldig tot',
        'label_certificaathouder' => 'Certificaathouder',
        'label_company' => 'PS Alltrain',
        'label_naam' => 'W. Diepeveen',
        'footer_left' => 'PS Alltrain<br>Snoeksloot 52<br>3993 HM Houten',
        'footer_right' => '085-2736434<br>info@psalltrain.nl<br>www.psalltrain.nl'
    );
    
    if( $data['p_geboortedatum'] != '' ) {
        $setup['label_geboortedatum'] = 'Geboortedatum';
    }
    
    if( $data['p_naam'] != '' ) {
        $data['p_naam'] = $name;
    }

    if( $data['p_geboortedatum'] != '' ) {
        $data['p_geboortedatum'] = date( 'd-m-Y', strtotime($birth_date));
    }
    
    if( $data['d_toetsingsdatum'] != '' ) {
        $data['d_toetsingsdatum'] = date( 'd-m-Y', strtotime($valid_from));
    }
    if( $data['d_geldig_tot'] != '' ) {
        $data['d_geldig_tot'] = date( 'd-m-Y', strtotime( $valid_untill ));
    }
    
    foreach( $setup as $key=>$value ) {
        echo '<div class="info" id="' .$key .'">' .$value .'</div>';
    }
    
    foreach( $data as $key=>$value ) {
        echo '<div class="info" id="' .strtolower($key) .'">' .$value .'</div>';
    }
    
    ?>
    <div class="info" id="c_titel">{{$company_name}}</div>
    <div class="info" id="c_subtitel">{{$course_name}}</div>

    @if(date('Y-m-d H:i:s', strtotime($expired_date)) < date('Y-m-d H:i:s'))
        <div class="info" id="certificate_expired">Expired</div>
    @endif

    <div class="info" id="certificate_url">{{ $url }}</div>
    <div class="info" id="certificate_created">{{ date('y-m-d H:i:s', strtotime($valid_from))}}</div>
    </body>
</html>
    