<script>
    function validateForm() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        var selectBox = document.querySelector('select[name="table"]');

        if (checkboxes.length < 1) {
            alert("Please check at least one checkbox.");
            return false;
        }

        if (selectBox.value == "") {
            alert("Please select an option from the select box.");
            return false;
        }

        return true;
    }
</script>