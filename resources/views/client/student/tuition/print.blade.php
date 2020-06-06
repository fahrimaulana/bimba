<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
         <style type="text/css">
            #content{
                font-size: 10px !important;
                width: 100%;
                font-family: Arial, Helvetica, sans-serif;
                line-height: 17px;
                text-align: left;
                font-weight: bold;
                padding-left: 20px;
                padding-top: 20px;
            }
            .text-center {
                text-align: center;
            }
            .bold {
                font-weight: bold !important;
            }
            body {
    display: block;
    margin-left: auto;
    margin-right: auto;
}
         </style>
    </head>
    <body>
<!--         <div id="header">
            <div class="text-center bold" style="">Kartu SPP Murid</div>
            <hr>
        </div> -->
        <div id="content">
            <table width="100%">
                <tr>
                    <td width="30%">NIM</td>
                    <td width="1%">:</td>
                    <td align="left">{{ $student->nim }}</td>
                </tr>
                <tr>
                    <td width="30%">Nama Murid</td>
                    <td width="1%">:</td>
                    <td align="left">{{ $student->name }}</td>
                </tr>
                <tr>
                    <td width="30%">Golongan</td>
                    <td width="1%">:</td>
                    <td align="left">{{ optional($student->masterClass)->code .' | '.optional($student->grade)->name }}</td>
                </tr>
                <tr>
                    <td width="30%">Pembayaran SPP</td>
                    <td width="1%">:</td>
                    <td align="left">{{ 'Rp '.thousandSeparator(optional($student)->fee + (int) $student->nim) }}</td>
                </tr>
                <tr>
                    <td width="30%">biMBA Unit</td>
                    <td width="1%">:</td>
                    <td align="left">{{ client()->name }}</td>
                </tr>
                <tr>
                    <td width="30%">No Pembayaran</td>
                    <td width="1%">:</td>
                    <td align="left">{{ client()->account_bank .' | '. client()->account_number }}</td>
                </tr>
            </table>
        </div>
    </body>
</html>