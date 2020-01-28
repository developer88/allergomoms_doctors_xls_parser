# allergomoms_doctors_xls_parser

Simple script to convert Excel sheet (`*.xlsx`) into json format to work with https://datatables.net jQuery plugin.

## Usage

⚠️ Keep in mind, that the library works only with `*.xlsx` files.

```bash
FILE=~/Downloads/список\ врачей.xlsx php src/parser.php
```

or if you have a direct link to download the file you can use this:

```bash
REMOTE=https://somefile-url.xlsx php src/parser.php
```

ℹ️ Check this [guide](https://www.labnol.org/internet/direct-links-for-google-drive/28356/) to find out how to get a direct download link for files in Google Drive.
