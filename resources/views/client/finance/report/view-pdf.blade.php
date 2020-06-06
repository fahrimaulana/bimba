<table width="100%" border="1">
   <tr style="background-color: #e2fafd;">
      <td colspan="4">
         <div align="center">SUMMARY KEUANGAN </div>
      </td>
   </tr>
   <tr width="100%">
      <td width="25%" valign="top">
         <table width="100%">
            <tr>
               <th colspan="2" class="text-center">Penerimaan</th>
            </tr>
            <tr>
               <td colspan="2">
                  <hr>
               </td>
            </tr>
            <tr>
               <td>Daftar</td>
               <td>{{ $result['transaction_registration'] }}</td>
            </tr>
            <tr>
               <td>SPP+VHB</td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>Penjualan</td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>Total</td>
               <td>{{ $result['total_transaction'] }}</td>
            </tr>
         </table>
      </td>
      <td width="25%">
         <table width="100%">
            <tr>
               <th colspan="2" class="text-center">Petty Cash </th>
            </tr>
            <tr>
               <td colspan="2">
                  <hr>
               </td>
            </tr>
            <tr>
               <td>Saldo Awal </td>
               <td>{{ $result['petty_cash']['initial_saldo'] }}</td>
            </tr>
            <tr>
               <td>Debit</td>
               <td>{{ $result['petty_cash']['debit'] }}</td>
            </tr>
            <tr>
               <td>Kredit</td>
               <td>{{ $result['petty_cash']['credit'] }}</td>
            </tr>
            <tr>
               <td>Saldo Akhir </td>
               <td>{{ $result['petty_cash']['final_saldo'] }}</td>
            </tr>
         </table>
      </td>
      <td width="25%">
         <table width="100%">
            <tr>
               <th colspan="2" class="text-center">SPP Murid </th>
            </tr>
            <tr>
               <td colspan="2">
                  <hr>
               </td>
            </tr>
            <tr>
               <td>SPP biMBA </td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>SPP English </td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>Bagi Hasil biMBA </td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>Bagi Hasil English </td>
               <td>&nbsp;</td>
            </tr>
         </table>
      </td>
      <td width="25%">
         <table width="100%">
            <tr>
               <th colspan="2" class="text-center">Lain-lain</th>
            </tr>
            <tr>
               <td colspan="2">
                  <hr>
               </td>
            </tr>
            <tr>
               <td>Penyerahan VHB </td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>Pemakaian VHB </td>
               <td>&nbsp;</td>
            </tr>
            <tr>
               <td>Gaji Staff </td>
               <td>{{ $result['staff_salary'] }}</td>
            </tr>
            <tr>
               <td>Progressive</td>
               <td>{{ $result['progressive'] }}</td>
            </tr>
         </table>
      </td>
   </tr>
</table>