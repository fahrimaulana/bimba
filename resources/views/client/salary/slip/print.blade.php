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
         <div class="header-title">SLIP PEMBAYARAN GAJI</div>
         <div class="content-header">
            <table width="100%">
               <tr>
                  <td><img src="{{asset('assets/images/logo-round.png')}}" width="100" height="100" /></td>
                  <td>
                     <table height="90" width="825">
                        <tr>
                           <td>No Induk </td>
                           <td>:</td>
                           <td>{{ $staff->nik }}</td>
                           <td>&nbsp;</td>
                           <td>biMBA Unit </td>
                           <td>:</td>
                           <td>{{ optional($staff->department)->name }}</td>
                        </tr>
                        <tr>
                           <td>Nama Staff </td>
                           <td>:</td>
                           <td>{{ $staff->name }}</td>
                           <td>&nbsp;</td>
                           <td>Tanggal Masuk </td>
                           <td>:</td>
                           <td>{{ optional($staff->joined_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                           <td>Jabatan</td>
                           <td>:</td>
                           <td>{{ optional($staff->position)->name }}</td>
                           <td>&nbsp;</td>
                           <td>Bulan </td>
                           <td>:</td>
                           <td>{{ $slipMonth }} </td>
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
      <table width="96%" align="center">
         <tr>
            @php
              $total = $totalIncome = $totalsalaryDeduction = 0;
              $totalIncome = $basicSalary = $daily = $functional = $allowanceTotal = $underPayement = $incomeOther = 0;
            @endphp
            <td width="45%" valign="top">
               <table width="100%">
                  <tr>
                     <td colspan="5">PENDAPATAN</td>
                  </tr>
                  <tr>
                     <td width="1%">a.</td>
                     <td width="65%">Gaji Pokok </td>
                     <td width="2%">:</td>
                     <td width="2%">Rp</td>
                     <td width="30%">
                        <div align="right">{{ $basicSalary = thousandseparator(optional($salaryIncome)->basic_salary) }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>b.</td>
                     <td>Tunjangan Harian </td>
                     <td>:</td>
                     <td>Rp</td>
                     <td>
                        <div align="right">{{ $daily = thousandseparator(optional($salaryIncome)->daily) }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>c.</td>
                     <td>Tunjangan Fungsional </td>
                     <td>:</td>
                     <td>Rp</td>
                     <td>
                        <div align="right">{{ $functional = thousandseparator(optional($salaryIncome)->functional) }}</div>
                     </td>
                  </tr>
                  @php
                    $allowanceTotal = 0;
                  @endphp
                  @foreach($allowanceGroups as $allowanceGroup)
                  <tr>
                     <td></td>
                     <td>{{ $allowanceGroup->name }}</td>
                     <td>:</td>
                     <td>Rp</td>
                     <td align="right">{{ thousandseparator($allowanceGroup->amount) }}</td>
                  </tr>
                  @php
                    $allowanceTotal += $allowanceGroup->amount;
                  @endphp
                  @endforeach
                  <tr>
                     <td>h.</td>
                     <td>Kekurangan Gaji () </td>
                     <td>:</td>
                     <td>Rp</td>
                     <td>
                        <div align="right">{{ $underPayement = thousandseparator(optional($salaryIncome)->underpayment) }}</div>
                     </td>
                  </tr>
                  <tr>
                     <td>i.</td>
                     <td>Lain-lain</td>
                     <td>:</td>
                     <td>Rp</td>
                     <td>
                        <div align="right">{{ $incomeOther = thousandseparator(optional($salaryIncome)->other) }}</div>
                     </td>
                  </tr>
                  <tr>
                    @php
                      $subTotalIncome = optional($salaryIncome)->basic_salary + optional($salaryIncome)->daily + optional($salaryIncome)->functional + $allowanceTotal + $underPayement + $incomeOther;
                      $totalIncome += $subTotalIncome;
                    @endphp
                     <td><strong>j.</strong></td>
                     <td><strong>Total Pendapatan </strong></td>
                     <td><strong>:</strong></td>
                     <td><strong>Rp</strong></td>
                     <td>
                        <div align="right"><strong>{{ $totalIncome }}</strong></div>
                     </td>
                  </tr>
                  <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>
                        <div align="right"></div>
                     </td>
                  </tr>
                  <tr>
                    @php
                      $total = $totalIncome + $totalsalaryDeduction;
                    @endphp
                     <td colspan="2" bgcolor="#00CCFF"><strong>Jumblah Yang Dibayarkan (h-q) </strong></td>
                     <td bgcolor="#00CCFF"><strong>:</strong></td>
                     <td bgcolor="#00CCFF"><strong>Rp</strong></td>
                     <td bgcolor="#00CCFF">
                        <div align="right" id="total"></div>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                     <td bgcolor="#FFFFFF">&nbsp;</td>
                     <td bgcolor="#FFFFFF">&nbsp;</td>
                     <td bgcolor="#FFFFFF">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="2" bgcolor="#FFFFFF">Yang menyerakan, </td>
                     <td colspan="3" bgcolor="#FFFFFF">Penerima,</td>
                  </tr>
                  <tr>
                     <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                     <td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                     <td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
                     <td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
                  </tr>
                  <tr>
                     <td colspan="2" bgcolor="#FFFFFF">Kepala Unit </td>
                     <td colspan="3" bgcolor="#FFFFFF">Motivator</td>
                  </tr>
               </table>
            </td>
            <td width="6%" valign="top">&nbsp;</td>
            <td width="45%" valign="top">
              @php
                  $totalsalaryDeduction = $sick = $leave = $alpha = $notActive = $overpayment = $deductionOther = 0;
              @endphp
               <table width="100%">
                  <tr>
                     <td colspan="7">POTONGAN</td>
                  </tr>
                  <tr>
                     <td width="1%">k.</td>
                     <td width="50%">Sakit</td>
                     <td width="5%">{{ $staff->sick_total }}</td>
                     <td width="10%">Hari</td>
                     <td width="2%">:</td>
                     <td width="2%">Rp</td>
                     <td width="30%">{{ $sick = thousandseparator(optional($salaryDeduction)->sick) }}</td>
                  </tr>
                  <tr>
                     <td>l.</td>
                     <td>Izin</td>
                     <td>{{ $staff->leave_total }}</td>
                     <td>Hari</td>
                     <td>:</td>
                     <td>Rp</td>
                     <td>{{ $leave = thousandseparator(optional($salaryDeduction)->leave) }}</td>
                  </tr>
                  <tr>
                     <td>m.</td>
                     <td>Alpa</td>
                     <td>{{ $staff->alpha_total }}</td>
                     <td>Hari</td>
                     <td>:</td>
                     <td>Rp</td>
                     <td>{{ $alpha = thousandseparator(optional($salaryDeduction)->alpha) }}</td>
                  </tr>
                  <tr>
                    <td>n.</td>
                    <td>Tidak Aktif </td>
                    <td>{{ $staff->not_active_total }}</td>
                    <td>Hari</td>
                    <td>:</td>
                    <td>Rp</td>
                    <td>{{ $notActive = thousandseparator(optional($salaryDeduction)->not_active) }}</td>
                  </tr>
                  <tr>
                    <td>o.</td>
                    <td>Kelebihan Gaji () </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>Rp</td>
                    <td>{{ $overpayment = thousandseparator(optional($salaryDeduction)->overpayment) }}</td>
                  </tr>
                  <tr>
                    <td>p.</td>
                    <td>Lain-lain</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>:</td>
                    <td>Rp</td>
                    <td>{{ $deductionOther = thousandseparator(optional($salaryDeduction)->other) }}</td>
                  </tr>
                  <tr>
                    @php
                        $subTotalSalaryDeduction = optional($salaryDeduction)->sick + optional($salaryDeduction)->leave + optional($salaryDeduction)->alpha + optional($salaryDeduction)->not_active + optional($salaryDeduction)->overpayment + optional($salaryDeduction)->other;
                        $totalsalaryDeduction += $subTotalSalaryDeduction;
                    @endphp
                    <td><strong>q.</strong></td>
                    <td><strong>Total Potongan </strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>:</strong></td>
                    <td><strong>Rp</strong></td>
                    <td><div id="total-salary-deduction"><strong>{{ thousandseparator($totalsalaryDeduction) }}</strong></div></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">Pengambilan Cuti </td>
                    <td>{{ $staff->furlough_total }}</td>
                    <td>Hari</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2">Rekening &amp; email: </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="7">
                       {{ $staff->account_bank }} | {{ $staff->account_number }} | {{ $staff->account_name }}
                    </td>
                  </tr>
                  <tr>
                    <td colspan="7">{{ $staff->email }}</td>
                  </tr>
                  <tr>
                    <td colspan="7">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="7">Keterangan:</td>
                  </tr>
                  <tr>
                    <td colspan="7"><em>- Potongan dengan Izin (Tunjangan Harian : 25 Hari Kerja) </em></td>
                  </tr>
                  <tr>
                    <td colspan="7"><em>- Potongan Tanta Izin (Take Home Pay : 25 Hari Kerja) </em></td>
                  </tr>
                  <tr>
                    <td colspan="7"><em>- Periode Absensi tgl 26 bulan lalu s/d tgl 25 bulan selanjutnya </em></td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>
