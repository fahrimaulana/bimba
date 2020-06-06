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
   padding-right : 23px;
   padding-left: 23px;
   }
</style>

<html>
   <head>
      <title>SLIP PEMBAYARAN GAJI</title>
   </head>
   <body>
      <div id="header">
         <div class="header-title">SLIP PEMBAYARAN PROGRESIF</div>
         <div class="content-header">
            <table width="100%">
               <tr>
                  <td><img src="{{asset('assets/images/logo-round.png')}}" width="100" height="100" /></td>
                  <td>
                     <table height="90" width="825">
                        <tr>
                           <td>No Induk </td>
                           <td>:</td>
                           <td>{{ $result['nik'] }}</td>
                           <td>&nbsp;</td>
                           <td>biMBA Unit </td>
                           <td>:</td>
                           <td>{{ $result['department'] }}</td>
                        </tr>
                        <tr>
                           <td>Nama Staff </td>
                           <td>:</td>
                           <td>{{ $result['name'] }}</td>
                           <td>&nbsp;</td>
                           <td>Tanggal Masuk </td>
                           <td>:</td>
                           <td>{{ $result['joined_date'] }}</td>
                        </tr>
                        <tr>
                           <td>Jabatan</td>
                           <td>:</td>
                           <td>{{ $result['position'] }}</td>
                           <td>&nbsp;</td>
                           <td>Bulan </td>
                           <td>:</td>
                           <td>{{ $result['month_paid'] }}</td>
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
         </div>
      </div>
      <table width="96%" align="center">
         <tr>
            <td width="45%" valign="top">
               <table width="100%">
                  <tr>
                     <td colspan="3">a. Rincian Murid</td>
                  </tr>
                  <tr>
                     <td width="65%">Murid Aktif (AM 1)</td>
                     <td>:</td>
                     <td>
                        <div>{{ $result['student_data']['active'] }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>Murid Aktif Yang Bayar SPP (AM 2)</td>
                     <td>:</td>
                     <td>
                        <div>{{ $result['student_data']['active_paid']['paid_count'] }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>Murid Garansi (MGRS)</td>
                     <td>:</td>
                     <td>
                        <div>{{ $result['student_data']['warranty'] }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>Murid Dhuafa (MDF)</td>
                     <td>:</td>
                     <td>{{ $result['student_data']['dhuafa'] }}</td>
                  </tr>
                  <tr>
                     <td>Murid BNF (MBNF 1)</td>
                     <td>:</td>
                     <td>
                        <div>{{ $result['student_data']['bnf'] }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>Murid BNF Yang Bayar SPP (MBNF 2)</td>
                     <td>:</td>
                     <td>
                        <div>{{ $result['student_data']['bnf_paid']['paid_count'] }}</div>
                     </td>
                  </tr>
                  @foreach ($departments as $department)
                    <tr>
                       <td>Murid Baru @if ($loop->iteration == 1) biMBA-AIUEO (MB) @else English biMBA (MBE) @endif</td>
                       <td>:</td>
                       <td>
                          <div>{{ $result['student_data']['department_'.$department->id]['mb'] }}</div>
                       </td>
                    </tr>
                    <tr>
                       <td>Murid Trial @if ($loop->iteration == 1) biMBA-AIUEO (MT) @else English biMBA (MTE) @endif</td>
                       <td>:</td>
                       <td>
                          <div>{{ $result['student_data']['department_'.$department->id]['mt'] }}</div>
                       </td>
                    </tr>
                  @endforeach
                  <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="3">b. Rincian Murid</td>
                  </tr>
                  @foreach ($departments as $department)
                  <tr>
                     <td>Penerimaan SPP @if ($loop->iteration == 1) biMBA-AIUEO @else English biMBA @endif</td>
                     <td>:</td>
                     <td>
                        <div>Rp. {{ thousandSeparator($result['money_order']['department_'.$department->id]['paid_total']) }}</div>
                     </td>
                  </tr>
                  @endforeach
                  <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td>Yang menyerakan, </td>
                     <td></td>
                     <td>Penerima,</td>
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                     <td></td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                     <td></td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                     <td></td>
                     <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td>&nbsp;&nbsp;&nbsp;&nbsp;Kepala Unit</td>
                     <td></td>
                     <td>Motivator</td>
                  </tr>
               </table>
            </td>
            <td width="45%" valign="top">
               <table width="100%">
                  <tr>
                     <td colspan="4">c. Rincian Pembayaran</td>
                  </tr>
                  <tr>
                     <td colspan="2">Total Seluruh FM</td>
                     <td>:</td>
                     <td>{{ $result['fm']['total'] }}</td>
                  </tr>
                  <tr>
                     <td colspan="2">Nilai Progresif</td>
                     <td>:</td>
                     <td>Rp. {{ thousandSeparator($result['progressive']) }}</td>
                  </tr>
                  <tr>
                     <td colspan="2">Total Komisi</td>
                     <td>:</td>
                     <td>Rp. {{ thousandSeparator($result['commission']['total']) }}</td>
                  </tr>
                  @foreach ($departments as $department)
                  <tr>
                     <td width="1%">&nbsp;&nbsp;&nbsp;</td>
                     <td>Komisi @if ($loop->iteration == 1) MB biMBA-AIUEO @else MB English biMBA @endif</td>
                     <td>:</td>
                     <td>Rp. {{ thousandSeparator($result['commission']['department_'.$department->id]['mb']) }}</td>
                  </tr>
                  <tr>
                     <td width="1%">&nbsp;&nbsp;&nbsp;</td>
                     <td>Komisi @if ($loop->iteration == 1) MT biMBA-AIUEO @else MT English biMBA @endif</td>
                     <td>:</td>
                     <td>Rp. {{ thousandSeparator($result['commission']['department_'.$department->id]['mt']) }}</td>
                  </tr>
                  @endforeach
                  <tr>
                     <td width="1%">&nbsp;&nbsp;&nbsp;</td>
                     <td>Komisi Asisten KU</td>
                     <td>:</td>
                     <td>Rp. {{ thousandSeparator($result['commission']['asku']) }}</td>
                  </tr>
                  <tr>
                     <td colspan="2">Total Pendapatan</td>
                     <td>:</td>
                     <td>Rp. {{ thousandSeparator($result['paid_out']) }}</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="4">d. Rincian Adjusment</td>
                  </tr>
                  <tr>
                     <td colspan="2">Kelebihan Progresif</td>
                     <td>:</td>
                     <td>Rp. 0</td>
                  </tr>
                  <tr>
                     <td colspan="2">Kekurangan Progresif</td>
                     <td>:</td>
                     <td>Rp. 0</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr bgcolor="#00CCFF">
                     <td colspan="2">Jumlah Yang Dibayarkan</td>
                     <td>:</td>
                     <td>Rp. {{ thousandSeparator($result['paid_out']) }}</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">Ditransfer Ke : </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" style="font-style: italic;">
                       {{ $result['account_bank'] }} | {{ $result['account_number'] }} | {{ $result['account_name'] }}
                    </td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
           <td colspan="2" width="100%">
             <hr>
           </td>
         </tr>
         <tr>
           <td colspan="2" width="100%">
             <table width="100%" border="1">
               <thead>
                 <tr bgcolor="#00CCFF">
                    <th align="center">NIM</th>
                    <th align="center">Nama Murid</th>
                    <th align="center">Kelas</th>
                    <th align="center">Gol</th>
                    <th align="center">KD</th>
                    <th align="center">SPP</th>
                    <th align="center">Status</th>
                    <th align="center">Note</th>
                    <th align="center">Cek</th>
                 </tr>
               </thead>
               <tbody>
                @foreach ($students as $student)
                 <tr>
                   <td align="center">{{ $student['nim'] }}</td>
                   <td align="center">{{ $student['name'] }}</td>
                   <td align="center">{{ $student['department'] }}</td>
                   <td align="center">{{ $student['code'] }}</td>
                   <td align="center">{{ $student['grade'] }}</td>
                   <td align="center">{{ $student['spp'] }}</td>
                   <td align="center">{{ $student['status'] }}</td>
                   <td align="center">{{ $student['note'] }}</td>
                   <td align="center"></td>
                 </tr>
                @endforeach
               </tbody>
             </table>
           </td>
         </tr>
      </table>
   </body>
</html>