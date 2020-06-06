<style type="text/css">
   body {
   border: solid #77d1dc;
   }
</style>
<body>
   <table width="100%" height="100%" style="background-color: #e2fafd;">
      <tr>
         <td valign="top">
            <table width="100%">
               <tr>
                  <td width="15%">
                     <img class="brand-img brand-striped w100" src="{{ asset('assets/images/logo-round.png') }}" width="200px" height="200px" margin-top="25px">
                  </td>
                  <td width="85%" valign="top">
                     <table width="100%">
                        <tr>
                           <td width="20%" valign="top">
                              <table width="100%">
                                 <tr>
                                    <th colspan="2" class="text-center">STAFF</th>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <hr>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Kepala Unit</td>
                                    <td>{{ $result['staff']['kepala_unit'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Asisten KU</td>
                                    <td>{{ $result['staff']['asisten_ku'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Guru</td>
                                    <td>{{ $result['staff']['guru'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Asisten Guru</td>
                                    <td>{{ $result['staff']['asisten_guru'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Staff Mobile</td>
                                    <td>{{ $result['staff']['staff_mobile'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Lain-lain</td>
                                    <td>{{ $result['staff']['others'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Total</td>
                                    <td>{{ $result['staff']['total'] }}</td>
                                 </tr>
                              </table>
                           </td>
                           <td width="20%" valign="top">
                              <table width="100%">
                                 <tr>
                                    <th colspan="2" class="text-center">MURID</th>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <hr>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Trial Baru</td>
                                    <td>{{ $result['student']['new_trial'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Aktif Bln Lalu</td>
                                    <td>{{ $result['student']['active_last_month'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Baru</td>
                                    <td>{{ $result['student']['new_active'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Keluar</td>
                                    <td>{{ $result['student']['out'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Aktif Bln Ini</td>
                                    <td>{{ $result['student']['active_current_month'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>Dhuafa</td>
                                    <td>{{ $result['student']['dhuafa'] }}</td>
                                 </tr>
                                 <tr>
                                    <td>BNF</td>
                                    <td>{{ $result['student']['bnf'] }}</td>
                                 </tr>
                              </table>
                           </td>
                           <td width="20%" valign="top">
                              <table width="100%">
                                 <tr>
                                    <th colspan="2" class="text-center">KEUANGAN</th>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <hr>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Penerimaan</td>
                                    <td>{{ thousandSeparator($result['finance']['transaction']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Petty Cash</td>
                                    <td>{{ thousandSeparator($result['finance']['petty_cash']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Pengeluaran</td>
                                    <td>{{ thousandSeparator($result['finance']['spending']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>SPP Murid</td>
                                    <td>{{ thousandSeparator($result['finance']['tuition']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Gaji Staff</td>
                                    <td>{{ thousandSeparator($result['finance']['benefit']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Progressive</td>
                                    <td>{{ thousandSeparator($result['finance']['progressive']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Bagi Hasil</td>
                                    <td>{{ thousandSeparator($result['finance']['profit_sharing']) }}</td>
                                 </tr>
                              </table>
                           </td>
                           <td width="20%" valign="top">
                              <table width="100%">
                                 <tr>
                                    <th colspan="2" class="text-center">MODUL</th>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <hr>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Saldo Awal</td>
                                    <td>{{ thousandSeparator($result['module']['initial_balance']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Penerimaan</td>
                                    <td>{{ thousandSeparator($result['module']['total_addition']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Pengeluaran</td>
                                    <td>{{ thousandSeparator($result['module']['total_deduction']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Saldo Akhir</td>
                                    <td>{{ thousandSeparator($result['module']['ending_balance']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Opname</td>
                                    <td>{{ thousandSeparator($result['module']['opname_balance']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>< Min Stok</td>
                                    <td>{{ thousandSeparator($result['module']['less_than_min_stock']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Selisih</td>
                                    <td>{{ thousandSeparator($result['module']['diff']) }}</td>
                                 </tr>
                              </table>
                           </td>
                           <td width="20%" valign="top">
                              <table width="100%">
                                 <tr>
                                    <th colspan="2" class="text-center">ORDER</th>
                                 </tr>
                                 <tr>
                                    <td colspan="2">
                                       <hr>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Modul</td>
                                    <td>{{ thousandSeparator($result['order']['module']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Kaos Anak</td>
                                    <td>{{ thousandSeparator($result['order']['ka']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Modul Eks</td>
                                    <td>{{ thousandSeparator($result['order']['ex_module']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Sertifikat</td>
                                    <td>{{ thousandSeparator($result['order']['certificate']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>STPB</td>
                                    <td>{{ thousandSeparator($result['order']['stpb']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>Tas biMBA</td>
                                    <td>{{ thousandSeparator($result['order']['bag']) }}</td>
                                 </tr>
                                 <tr>
                                    <td>ATK/Perlengkapan</td>
                                    <td>{{ thousandSeparator($result['order']['atk']) }}</td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
      <tr>
         <td valign="top">
            <table width="100%">
               <tr>
                  <td valign="top">
                     <table width="100%">
                        <tr>
                           <td width="55%" valign="top">
                              <table width="100%">
                                 <tr>
                                    <td valign="top">
                                       <div class="info font-size-12 style-bold">
                                          <ol type="a">
                                             <li><b>Teknis Pengiriman Laporan</b></li>
                                             <ol>
                                                <li>Baik laporan aplikasi ataupun bukti fisik dikirim setiap minggu sekali.</li>
                                                <li>Aturan Periode Laporan dimulai dari hari Sabtu s/d hari Jumat.</li>
                                                <li>
                                                   Laporan aplikasi dikirim melalui email, yaitu :
                                                   <h5 font-size-11>
                                                      Kirim : <font color="blue">laporan.admunit@gmail.com, laporan.modulbimba@gmail.com, laporan.sertifikat@gmail.com</font>
                                                      <p>Cc : <font color="blue">kadiv.operasionalbimba@gmail.com, laporan.hrd@gmail.com</font>
                                                      </p>
                                                   </h5>
                                                </li>
                                                <li>
                                                   Bukti fisik dikirim melalui staff SOS atau jasa ekspedisi ke
                                                   <h5>
                                                      Kantor pusat administrasi biMBA-AIUEO di alamat :
                                                      <p><i>Jl. Tanjung Duren Timur 6 No 213, Tanjung Duren Selatan, Grogol Petamburan, Jakarta Barat. Telp 021-5668719.</i></p>
                                                   </h5>
                                                </li>
                                             </ol>
                                             <li><b>Sanksi Pelanggaran</b></li>
                                             <ol>
                                                <li>Bagi yang terlambat mengirimkan laporan akan diberikan sanksi berupa surat Teguran/Peringatan.</li>
                                                <li>Bagi yang tidak mengisi laporan dengan lengkap dan jelas akan diberikan sanksi berupa surat Teguran/Peringatan.</li>
                                                <li>Jika ditemukan ketidaksesuaian ataaupun manipulasi laporan maka akan diberikan sanksi Skorsing/SP 3.</li>
                                             </ol>
                                          </ol>
                                       </div>
                                    </td>
                                 </tr>
                              </table>
                           </td>
                           <td width="45%" valign="top">
                               <table width="100%">
                                   <tr>
                                       <td>
                                           <h4 class="brand-text font-size-12"><b>DAFTAR KONTAK STAFF ADMIN PUSAT</b></h4>
                                       </td>
                                   </tr>
                                   <tr>
                                       <td>
                                 <table width="100%">
                                    <tr style="background-color: #d2d5ff">
                                       <th>No</th>
                                       <th>Telp/HP</th>
                                       <th align="text-center">Bagian</th>
                                       <th>PIC</th>
                                    </tr>
                                    <tr align="center">
                                       <td>1.</td>
                                       <td>081586661372</td>
                                       <td>Rekruitmen</td>
                                       <td>Ibu Mega</td>
                                    </tr>
                                    <tr align="center">
                                       <td>2.</td>
                                       <td>081510002372</td>
                                       <td>Personalia</td>
                                       <td>Ibu Tia</td>
                                    </tr>
                                    <tr align="center">
                                       <td>3.</td>
                                       <td>081586666372</td>
                                       <td>Admin SOS</td>
                                       <td>Bpk Satria</td>
                                    </tr>
                                    <tr align="center">
                                       <td>4.</td>
                                       <td>08158446372</td>
                                       <td>Laporan</td>
                                       <td>Ibu Anisa</td>
                                    </tr>
                                    <tr align="center">
                                       <td>5.</td>
                                       <td>08159292372</td>
                                       <td>Data Murid</td>
                                       <td>Bpk Yogi</td>
                                    </tr>
                                    <tr align="center">
                                       <td>6.</td>
                                       <td>081585858372</td>
                                       <td>Penerimaan</td>
                                       <td>Ibu Imas</td>
                                    </tr>
                                    <tr align="center">
                                       <td>7.</td>
                                       <td>081511041372</td>
                                       <td>Petty Cash</td>
                                       <td>Ibu Lina</td>
                                    </tr>
                                    <tr align="center">
                                       <td>8.</td>
                                       <td>08164836372</td>
                                       <td>VHB & Bea</td>
                                       <td>Bpk Dewan</td>
                                    </tr>
                                    <tr align="center">
                                       <td>9.</td>
                                       <td>081585807372</td>
                                       <td>ATM Simpel</td>
                                       <td>Ibu Sindi</td>
                                    </tr>
                                    <tr align="center">
                                       <td>10.</td>
                                       <td>081510201372</td>
                                       <td>Laporan & STA/STPB</td>
                                       <td>Bpk Riski</td>
                                    </tr>
                                    <tr align="center">
                                       <td>11.</td>
                                       <td>081510207372</td>
                                       <td>IT Unit</td>
                                       <td>Bpk Ali</td>
                                    </tr>
                                    <tr align="center">
                                       <td>12.</td>
                                       <td>08558432372</td>
                                       <td>Modul</td>
                                       <td>Bpk Panca</td>
                                    </tr>
                                    <tr align="center">
                                       <td>13.</td>
                                       <td>081584410372</td>
                                       <td>Fasilitas Unit</td>
                                       <td>Ibu Jelita</td>
                                    </tr>
                                    <tr align="center">
                                       <td>14.</td>
                                       <td>081574634372</td>
                                       <td>Maintenance</td>
                                       <td>Ibu Dina</td>
                                    </tr>
                                 </table>
                                       </td>
                                   </tr>
                               </table>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
</body>