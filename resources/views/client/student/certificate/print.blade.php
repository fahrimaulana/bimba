<html>
    <head>
        <title>Sertifikat Beasiswa Pendidikan</title>
        <style>
            #padding-paper {
              padding-top: 400px !important;
              padding-left: 60px !important;
            }
            .header {
              font-family: "Arial Verdana", Times, sans-serif;
              font-size:24px;
              text-align: center;
              font-weight: bold;
            }

            .p-content {
              font-family: "Arial Verdana", Times, sans-serif;
              padding-left: 350px !important;
              font-size:20px;
            }
        </style>
    </head>
    <body>
        <div id="padding-paper">
            <div class="header">Sebesar Rp. {{ thousandSeparator($sertificat->amount) }}</div>
            <div class="header">{{ $sertificat->amount_written }}</div>
            <p>
              <div class="p-content">{{ $student->name }}</div>
              <div class="p-content">{{ optional($sertificat->change_date)->format('d F Y') }}</div>
              <div class="p-content">{{ $student->address }}</div>
              <div class="p-content">{{ $sertificat->person_in_charge }}</div>
            </p>
        </div>
    </body>
</html>