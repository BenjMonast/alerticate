<label for="start-date">Start Date:</label>
<input name="start-date" type="date" value="{{ substr(\Carbon\Carbon::now($timezone), 0, 10) /*I used substr to only get the date part*/ }}" id="start-date">
<input type="checkbox" id="none-checkbox">
<label for="none-checkbox">None</label>
<br>
<label for="end-date">Date of Return: </label>
<input class="starttrip-fields" name="end-date" type="date" value="{{ substr(\Carbon\Carbon::now($timezone), 0, 10)}}" id="end-date" required>
<br>
<label for="end-time">Time of Return: </label>
<input class="starttrip-fields" name="end-time" type="text" id="end-time" required>
<br>
<input type="checkbox" name="email" id="email-checkbox">
<label for="email-checkbox">My contacts will be notified that I started this trip</label>
<p id='error'></p>