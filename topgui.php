<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">

<body>
<title>attendance system tests</title>

<table style="width : 80%">
<tr>
    <td><input type="submit" value="Present" name="present"></td>
</tr>
<tr>
    <td><input type="submit" value="Offsite" name="offsite"></td>
    <td>
        <input type="text" name="location">
        <label for="location">Location</label></td>
        <td>
        <input type="text" name="offtime">
        <label for="offtime">Return time</label></td>
</tr>
<tr>
    <td><input type="submit" value="Field Trip" name="fieldtrip"></td>
    <td><select>
        <option value="scobie">Scobie</option>
        <option value="nic">Nic</option>
        <option value="crysta">Crysta</option>
        </select>
    </td>
    <td>
        <input type="text" name="ftlocation">
        <label for="ftlocation">Location</label>
        <input type="text" name="fttime">
        <label for="fttime">Return time</label>
        </td>
<tr>
    <td><input type="submit" value="Sign Out" name="signout"></td>
</tr>
</table>    
</body>
</html>