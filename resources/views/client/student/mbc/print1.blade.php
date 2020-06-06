<style type="text/css">
  body {
    border: solid #77d1dc;
  }
  #header {
   margin-top: 0px;
   margin-bottom: 0px;
   margin-left: 0px;
   margin-right: 0px;
  }
  .header-title {
   background-color: #77d1dc;
   text-align: center;
   padding-top: 5px;
   padding-bottom: 5px;
  }
  .content-header {
   margin-left: 20px;
   margin-top: 10px;
  }
  .header {
   height: 90px;
   width: 825px;
  }
  hr{
    border-style: solid;
    margin-right : 30px;
    margin-top: 5px;
    color: #10f70c;
  }
  #table {
    border: 2px solid #77D1DC;
    border-spacing: inherit;
  }
  tr td #table {
        border-collapse: collapse;
  }
</style>
<html>
    <head>
        <title>MBC Murid</title>
    </head>
    <body>
        <div id="header">
         <div class="header-title">NOMOR PEMBAYARAN MURID biMBA-AIUEO</div>
         <div class="content-header">
            <table width="100%">
               <tr>
                  <td><img src="{{asset('assets/images/logo-mandiri.png')}}" width="120" height="158" /></td>
                  <td>
                     <table height="90" width="825">
                        <tr>
                          <th style="text-align: left"><u>Profil Unit</u></th>
                            <td></td>
                            <td></td>
                            <td></td>
                          <th style="text-align: left"><u>Profil Murid</u></th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                           <td>No Cabang </td>
                           <td>:</td>
                           <td>{{ $client->code }}</td>
                           <td>&nbsp;</td>
                           <td>NIM </td>
                           <td>:</td>
                           <td>{{ $student->nim }}</td>
                        </tr>
                        <tr>
                           <td>Nama Unit </td>
                           <td>:</td>
                           <td>{{ $client->name }}</td>
                           <td>&nbsp;</td>
                           <td>Nama Murid</td>
                           <td>:</td>
                           <td>{{ $student->name }}</td>
                        </tr>
                        <tr>
                           <td>No Telp/HP</td>
                           <td>:</td>
                           <td>{{ $client->phone }}</td>
                           <td>&nbsp;</td>
                           <td>Kelas</td>
                           <td>:</td>
                           <td>{{ optional($student->department)->code . ' | ' . optional($student->department)->name }}</td>
                        </tr>
                        <tr>
                           <td>Bank</td>
                           <td>:</td>
                           <td>{{ $client->account_bank }}</td>
                           <td>&nbsp;</td>
                           <td>Gol & Kode</td>
                           <td>:</td>
                           <td>{{ optional($student->masterClass)->code .' | '.optional($student->grade)->name }}</td>
                        </tr>
                        <tr>
                           <td>No Rekening</td>
                           <td>:</td>
                           <td>{{ $client->account_number }}</td>
                           <td>&nbsp;</td>
                           <td>SPP</td>
                           <td>:</td>
                           <td>{{ 'Rp '.thousandSeparator(optional($student)->fee + (int) $student->nim) }}</td>
                        </tr>
                        <tr>
                           <td>Atas Nama</td>
                           <td>:</td>
                           <td>{{ $client->account_name }}</td>
                           <td>&nbsp;</td>
                           <td>Wali Murid</td>
                           <td>:</td>
                           <td>{{ $student->parent_name }}</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="2">
                     <hr>
                  </td>
               </tr>
            </table>
           <table class="content-table" style="float: left; width: 100%">
                <tr>
                 <th style="text-align: left;">1. BILL PAYMENT <font color="#10f70c">(VIA BANK MANDIRI)</font></th>
                </tr>
                 <tr>
                    <th width="100%" style="text-align: left;">&nbsp; &nbsp; a) Format Kode Nomor Pembayaran</th>
                 </tr>
             </table>
             <table class="content-table" id="table" style="float: left; width: 50%">
                 <tr>
                    <th width="29%" height="50" style="background-color: #77d1dc">Keterangan</th>
                    <th width="25%" style="background-color: #77d1dc">Kode Kelas</th>
                    <th width="25%" style="background-color: #77d1dc">No NO NIM</th>
                 </tr>
                 <tr align="center">
                     <td id="table" height="40">Jumlah</td>
                     <td id="table">2 Digit</td>
                     <td id="table">9 Digit</td>
                 </tr>
                 <tr align="center">
                     <td id="table" rowspan="2">Bill Payment</td>
                     <td id="table">{{ optional($student->department)->code }}</td>
                     <td id="table" height="40">{{ $clientCode }}{{ substr($student->nim,4) }}</td>
                 </tr>
                 <tr align="center">
                     <td id="table" colspan="2" height="40"><font color="#0B02D9" size=5px>{{ optional($student->department)->code }} - {{ $clientCode }}{{ substr($student->nim,4) }}</font></td>
                 </tr>
             </table>
             <table class="content-table" style="float: left; width: 100%">
                 <tr>
                    <th width="100%" style="text-align: left;">&nbsp; &nbsp; b) Cara Pembayaran</th>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masuk Ke Menu <b>PEMBAYARAN</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Pilih Pembayaran <b>PENDIDIKAN</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Pilih Penyedia Jasa <b>AIUEO biMBA</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masukkan nomor <b>BILL PAYMENT</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Tekan <b>Benar/Lanjut</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Jika profil & Tagihan murid yang tertera sesuai maka <br> &emsp; &emsp; &emsp; tekan <b>Benar/Lanjut</b>, jika tidak tekan <b>Salah/Cancel</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; <font color="red"><i>Simpan struk ATM sebagai bukti pembayaran</i></font></td>
                 </tr>
             </table>
             <table class="content-table" style="float: left; width: 100%">
                <tr>
                 <th style="text-align: left;">2. VIRTUAL ACCOUNT <font color="#10f70c">(VIA BANK LAIN)</font></th>
                </tr>
                 <tr>
                    <th width="100%" style="text-align: left;">&nbsp; &nbsp; a) Format Kode Nomor Pembayaran</th>
                 </tr>
             </table>
             <table id="table" class="content-table" style="float: left; width: 55%">
                 <tr>
                    <th width="25%" height="50" style="background-color: #77d1dc">Keterangan</th>
                    <th width="20%" style="background-color: #77d1dc">Kode Bank</th>
                    <th width="20%" style="background-color: #77d1dc">Kode Kelas</th>
                    <th width="20%" style="background-color: #77d1dc">No NIM</th>
                 </tr>
                 <tr align="center">
                     <td id="table" height="40">Jumlah</td>
                     <td id="table">5 Digit</td>
                     <td id="table">2 Digit</td>
                     <td id="table">9 Digit</td>
                 </tr>
                 <tr align="center">
                     <td id="table" rowspan="2">Bill Payment</td>
                     <td id="table">{{ $kodeBank }}</td>
                     <td id="table" height="40">{{ optional($student->department)->code }}</td>
                     <td id="table">{{ $clientCode }}{{ substr($student->nim,4) }}</td>
                 </tr>
                 <tr align="center">
                     <td id="table" colspan="3" height="40"><font color="#0B02D9" size=5px>{{ $kodeBank }} - {{ optional($student->department)->code }} - {{ $clientCode }}{{ substr($student->nim,4) }}</font></td>
                 </tr>
             </table>
             <table class="content-table" style="float: left; width: 50%">
                 <tr>
                    <th width="100%" style="text-align: left;">&nbsp; &nbsp; b) Cara Pembayaran (Via Bank Mandiri)</th>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masuk ke menu <b>Pembayaran (Bayar/Beli)</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Pilih <b>KE MENU PENDIDIKAN</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masukkan kode <b>Kode Perusahaan {{ $kodeBank }} (jika diminta)</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Atau Pilih <b>AIUEO biMBA VA</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masukkan nomor <b>REKENING MURID</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masukkan jumlah <b>TAGIHAN SPP</b></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Jika profil murid yang tertera sesuai maka tekan <br> &emsp; &emsp; &emsp; <font color="red"><i>Benar/Lanjut, jika tidak tekan Salah/Cancel</i></font></td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; <font color="red"><i>Simpan struk ATM sebagai bukti pembayaran</i></font></td>
                 </tr>
             </table>
             <table class="content-table" style="float: left; width: 50%">
                 <tr>
                    <th width="100%" style="text-align: left;">&nbsp; &nbsp; b) Cara Pembayaran (Via Bank Lain)</th>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masuk ke menu TRANSFER</td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Pilih KE REKENING BANK LAIN</td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masukkan kode BANK MANDIRI (008)</td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masukkan jumlah TAGIHAN SPP</td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Masukkan nomor REKENING MURID</td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; Jika profil murid yang tertera sesuai maka tekan <br> &emsp; &emsp; &emsp; Benar/Lanjut, jika tidak tekan Salah/Cancel</td>
                 </tr>
                 <tr>
                     <td>&emsp; &emsp; &#8594; <font color="red"><i>Simpan struk ATM sebagai bukti pembayaran</i></font></td>
                 </tr>
             </table>
             <span style="clear:both;display: block"></span>
         </div>
      </div>
    </body>
</html>