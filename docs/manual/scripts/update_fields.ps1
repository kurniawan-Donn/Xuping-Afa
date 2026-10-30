# Mengisi Daftar Isi, Daftar Gambar, Daftar Tabel, dan nomor halaman memakai Microsoft Word,
# lalu menyimpan salinan PDF untuk pemeriksaan.
#
#   powershell -ExecutionPolicy Bypass -File docs\manual\scripts\update_fields.ps1

$ErrorActionPreference = 'Stop'
$manual = Split-Path -Parent $PSScriptRoot
$docx = Get-ChildItem -Path $manual -Filter 'Buku_Panduan_*.docx' | Where-Object { $_.Name -notlike '~$*' } | Select-Object -First 1
if (-not $docx) { throw 'File Buku_Panduan_*.docx belum ada. Jalankan build_docx.py terlebih dahulu.' }
$pdf = [System.IO.Path]::ChangeExtension($docx.FullName, '.pdf')

$word = New-Object -ComObject Word.Application
$word.Visible = $false
$word.DisplayAlerts = 0
try {
    $doc = $word.Documents.Open($docx.FullName, $false, $false)
    # Dua kali: putaran pertama mengisi daftar, putaran kedua membetulkan nomor halaman
    # yang bergeser karena daftar tersebut bertambah panjang.
    for ($i = 0; $i -lt 2; $i++) {
        $doc.Fields.Update() | Out-Null
        foreach ($toc in $doc.TablesOfContents) { $toc.Update() }
        foreach ($tof in $doc.TablesOfFigures) { $tof.Update() }
    }
    $doc.Save()
    $doc.ExportAsFixedFormat($pdf, 17)
    Write-Output ("Halaman: " + $doc.ComputeStatistics(2))
    $doc.Close($false)
}
finally {
    $word.Quit()
    [System.Runtime.InteropServices.Marshal]::ReleaseComObject($word) | Out-Null
}
Write-Output "Selesai: $($docx.FullName)"
Write-Output "PDF    : $pdf"
