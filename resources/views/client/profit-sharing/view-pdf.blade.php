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
    color: #77d1dc;
  }
  .content-table {
    padding-left: 23px;
    padding-right: 23px;
  }
</style>
<html>
   <head>
      <title>Laporan Bagi Hasil</title>
   </head>
   <body>
      <div id="header">
         <div class="header-title">Laporan Bagi Hasil</div>
         <div class="content-header">
            <table width="100%">
               <tr>
                  <td><img src="{{asset('assets/images/logo-round.png')}}" width="100" height="100" /></td>
                  <td>
                     <table height="90" width="825">
                        <tr>
                           <td>No Cabang </td>
                           <td>:</td>
                           <td>{{ $client->code }}</td>
                           <td>&nbsp;</td>
                           <td>Nama Bank </td>
                           <td>:</td>
                           <td>{{ $client->account_bank }}</td>
                        </tr>
                        <tr>
                           <td>biMBA Unit </td>
                           <td>:</td>
                           <td>{{ $client->name }}</td>
                           <td>&nbsp;</td>
                           <td>No Rekening </td>
                           <td>:</td>
                           <td>{{ $client->account_number }}</td>
                        </tr>
                        <tr>
                           <td>Bulan</td>
                           <td>:</td>
                           <td>{{ date("F", mktime(0, 0, 0, month(), 1)) }} {{ year() }}</td>
                           <td>&nbsp;</td>
                           <td>Atas Nama </td>
                           <td>:</td>
                           <td>{{ $client->account_name }}</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="2">
                     <hr>
                     <hr>
                  </td>
               </tr>
            </table>
         </div>
      </div>
      @foreach ($departments as $department)
      <table class="content-table" style="float: left; width: 50%">
         <tr>
            <th colspan="3" style="text-align: left;">{{ $department->name }}</th>
         </tr>
         <tr>
            <td width="30%" style="text-align: left;">Murid Aktif Bulan Lalu </td>
            <td width="3%">:</td>
            <td width="15%" style="text-align: right;">{{ thousandSeparator($department->active_previous_month) }}</td>
         </tr>
         <tr>
            <td>Murid Baru Bulan ini</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->new_this_month) }}</td>
         </tr>
         <tr>
            <td>Murid Aktif Kembali</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->active_again) }}</td>
         </tr>
         <tr>
            <td>Murid Keluar Bulan ini</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->out_student) }}</td>
         </tr>
         <tr>
            <td>Murid Aktif Bulan ini</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->active_this_month) }}</td>
         </tr>
         <tr>
            <td>Murid Dhuafa</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->dhuafa_count) }}</td>
         </tr>
         <tr>
            <td>Murid BNF</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->bnf_count) }}</td>
         </tr>
         <tr>
            <td>Murid Garansi</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->guarantee) }}</td>
         </tr>
         <tr>
            <td>Murid Deposit</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->deposit) }}</td>
         </tr>
         <tr>
            <td>Murid Piutang </td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->debt) }}</td>
         </tr>
         <tr>
            <td>Murid Yang Harus Bayar SPP</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->student_to_pay_tuition) }}</td>
         </tr>
         <tr>
            <td>Murid Yang Sudah Bayar SPP </td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->paid_tuition) }}</td>
         </tr>
         <tr>
            <td>Murid Yang Belum Bayar SPP</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->not_paid_tuition) }}</td>
         </tr>
         <tr>
            <td>Total Penerimaan SPP</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->total_tuition) }}</td>
         </tr>
         <tr>
            <td>Persentase Bagi Hasil</td>
            <td>:</td>
            <td style="text-align: right;">{{ $profitSharingPercentage }} %</td>
         </tr>
         <tr>
            <td>Jumlah Bagi Hasil</td>
            <td>:</td>
            <td style="text-align: right;">{{ thousandSeparator($department->total_tuition * $profitSharingPercentage / 100) }}</td>
         </tr>
      </table>
      @endforeach
      <span style="clear:both;display: block"></span>
   </body>
</html>