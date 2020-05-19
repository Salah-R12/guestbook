Cache :
url  -I -X PURGE -u admin:admin "https://127.0.0.1:8000/admin/http-cache/conference_header" --ssl-no
curl  -I -X PURGE -u admin:admin "https://127.0.0.1:8000/admin/http-cache" --ssl-no-revoke