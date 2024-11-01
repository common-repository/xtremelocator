<script type="text/javascript">
    function NewWindow(mypage, myname, w, h, scroll) {
        var winl = (screen.width - w) / 2;
        var wint = (screen.height - h) / 2;
        winprops = 'height=' + h + ',width=' + w + ',top=' + wint + ',left=' + winl + ',scrollbars=' + scroll + ',resizable'
        win = window.open(mypage, myname, winprops)
        if (parseInt(navigator.appVersion) >= 4) {
            win.window.focus();
        }
    }

    function checkZip() {
        if (document.getElementById("xl_search_type").value == 1) {
            zip = document.getElementById("XlocatorZip");
            city = document.getElementById("xl_search_city");
            if (isNaN(zip.value)) {
                city.value = zip.value;
                zip.value = '';
            }
        }
    }
</script>