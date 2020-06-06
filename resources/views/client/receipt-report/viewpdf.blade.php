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
    padding-right: 23px;
  }
  tr .table {
  border: solid #77d1dc;
  }
  td #table-content {
    border: solid #77d1dc;
  }
  .table {
    border-spacing: inherit;
  }
</style>
<html>
   <head>
      <title>Laporan Tanda Terima</title>
   </head>
   <body>
   	<div id="header">
   		<div class="header-title">TANDA TERIMA LAPORAN biMBA</div>
	  <div class="content-header">
   			<table width="100%">
   				<tr>
   					<td width="11%"><img src="{{asset('assets/images/logo-round.png')}}" width="100" height="100" /></td>
					<td width="89%">
						<table height="20">
							<tr>
								<td width="124">biMBA Unit</td>
								<td id="table-content" colspan="2">{{ $client->name }}</td>
							</tr>
							<tr class="table">
								<td width="124">Periode Tgl</td>
								<td width="90" class="table" style="border-collapse: collapse"></td>
								<td width="90" id="table-content" style="border-spacing: inherit"></td>
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
	   <table class="content-table" style="float: left; width: 100%">
      <tr>
        <td colspan="3" style="text-align: left;">Berkas yang diserahkan adalah :</td>
      </tr>
    </table>
   <table width="35%" class="content-table" style="float: left; width: 50%">
        <tr>
         <th style="text-align: left;"><u>1. Dokumen Staff</u></th>
      	</tr>
         <tr>
            <td width="50%" style="text-align: left;"><li type="1">1 CV Pelamar</li></td>
            <td class="table" width="50%"></td>
         </tr>
         <tr>
            <td><li type="1">2 Absensi</li></td>
            <td class="table"></td>
         </tr>
         <tr>
            <td><li type="1">3 Surat Keterangan Dokter</li></td>
            <td class="table"></td>
         </tr>
         <tr>
            <td><li type="1">4 Form Izin</li></td>
            <td class="table"></td>
         </tr>
         <tr>
            <td><li type="1">5 Form Cuti</li></td>
            <td class="table"></td>
         </tr>
         <tr>
            <td><li type="1">6 Materi Meeting KU/BBB</li></td>
            <td class="table"></td>
         </tr>
         <tr>
            <td><li type="1">7 Syarat Buat Rekening</li></td>
            <td class="table"></td>
         </tr>
         <tr>
            <td><li type="1">8 Tanda Terima ST/SP</li></td>
            <td class="table"></td>
         </tr>
	    <tr>
			<th style="text-align: left;"><u>2. Dokumen Murid</u></th>
      	</tr>
	     <tr>
            <td width="50%" style="text-align: left;">2.1 Form Pendaftaran</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">2.2 Persyaratan Murid Dhuafa</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">2.3 Sertifikat Beasiswa</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">2.4 Syarat Simpel Mandiri</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">2.5 Form Murid Pindahan</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">2.6 Foto Copy Raport Murid</td>
            <td class="table" width="50%"></td>
         </tr>
      </table>
	  <table width="35%" class="content-table" style="float: left; width: 50%">
        <tr>
         <th style="text-align: left;"><u>3. Dokumen Keuangan</u></th>
      	</tr>
         <tr>
            <td width="50%" style="text-align: left;">3.1 Voucher SPP</td>
            <td class="table" width="50%"></td>
         </tr>
         <tr>
            <td>3.2 Kwitansi Penerimaan</td>
            <td class="table"></td>
         </tr>
         <tr>
            <td>3.3 Bukti Pengeluaran</td>
            <td class="table"></td>
         </tr>
         <tr>
            <td>3.4 Pernyataan Blm Bayar SPP</td>
            <td class="table"></td>
         </tr>
         <tr>
            <td>3.5 Slip Gaji Staf</td>
            <td class="table"></td>
         </tr>
         <tr>
            <td>3.6 Slip Gaji Progressive</td>
            <td class="table"></td>
         </tr>
         <tr>
            <td>3.7 Bukti Transfer Bagi Hasil</td>
            <td class="table"></td>
         </tr>
         <tr>
            <td>3.8 Tanda Terima VHB/KW</td>
            <td class="table"></td>
         </tr>
	    <tr>
			<th style="text-align: left;"><u>4. Dokumen Lain-lain</u></th>
      	</tr>
	     <tr>
            <td width="50%" style="text-align: left;">4.1 Tanda Terima Modul</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">4.2 Form Pengambilan Modul</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">4.3 Data Stock Opname</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">4.4 Form Pengajuan Fasilitas</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">4.5 Proposal Pentas Baca</td>
            <td class="table" width="50%"></td>
         </tr>
	     <tr>
            <td width="50%" style="text-align: left;">4.6 Form Lain-lain</td>
            <td class="table" width="50%"></td>
         </tr>
      </table> 
      <span style="clear:both;display: block"></span>
	<tr>
	  <td colspan="2">
		 <hr>
		 <hr>
	  </td>
	</tr>
	</tr>
	<table class="content-table" style="float: left; width: 100%">
      <tr>
        <td colspan="3" style="text-align: left;">Tanggal :</td>
      </tr>
    </table>
	<table class="content-table" style="float: left; width: 100%">
      <tr>
        <td width="80%" style="text-align: left;">Yang Menyerahkan, </td>
		<td width="20%" style="text-align: left">Yang Menerima, </td>
      </tr>
		<tr>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		</tr>
		<tr>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		</tr>
		<tr>
		   <td>&nbsp;</td>
		   <td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<hr width="18%" align="left" color="black">
			</td>
			<td>
				<hr width="75%" align="left" color="black">
			</td>
		</tr>
		<tr>
			<td width="80%" style="text-align: left">Nama :</td>
			<td width="20%" style="text-align: left">Nama :</td>
		</tr>
    </table>
</div>
</div>
</body>
