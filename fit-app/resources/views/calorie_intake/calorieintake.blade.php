<form id="calorie-intake-form">
    <div class="form-group">
        <label for="weight-goal">Weight Goal:</label>
        <select id="weight-goal" class="form-control">
            <option value="loss">Weight Loss</option>
            <option value="maintain">Weight Maintenance</option>
            <option value="gain">Weight Gain</option>
        </select>
    </div>
    <div class="form-group">
        <label for="weight">Weight (in kg):</label>
        <input type="number" id="weight" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Calculate</button>
</form>

<div id="calorie-result"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#calorie-intake-form').submit(function(e) {
            e.preventDefault();

            var weightGoal = $('#weight-goal').val();
            var weight = $('#weight').val();

            $.ajax({
                url: '{{ route('calorie-intake.calculate') }}',
                method: 'POST',
                data: {
                    weight_goal: weightGoal,
                    weight: weight
                },
                success: function(response) {
                    $('#calorie-result').html(response);
                }
            });
        });
    });
</script>
