<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users from the database
$result = $conn->query("SELECT * FROM user_management");

?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['user_type']; ?></td>
            <td><?php echo str_repeat('*', strlen($row['password'])); ?></td> <!-- Masked password -->
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['department']; ?></td>
<td>
    <?php if ($row['user_image']) { ?>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['user_image']); ?>" width="40" height="40" />
    <?php } else { ?>
        No Image
    <?php } ?>
</td>
<td>
    <button onclick="editUser(<?php echo $row['id']; ?>)">Edit</button>
    <button onclick="deleteUser(<?php echo $row['id']; ?>)">Delete</button>
</td>

            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $conn->close(); ?>

<style>
    .form-container {
        margin-top: 20px;
        width: 100%;
        max-width: 2000px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        font-size: 12px;
        margin-bottom: 5px;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 6px;
        font-size: 12px;
        border: 1px solid #ddd;
        border-radius: 5px; 
    }

    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .form-grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }

    
    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 40px; 
    }
    .user-table th, .user-table td {
    border: 1px solid #ddd;
    padding: 6px;
    text-align: left;
    font-size: 12px; 
    }

    .user-table th {
    background-color: #f4f4f4;
    font-size: 13px; 
    font-weight: bold;
    }

    .user-table img {
    width: 30px; 
    height: 30px;
    object-fit: cover;
    }


    
    .actions {
        display: flex;
        justify-content: space-evenly;
        margin-top: 20px;
        gap: 10px;
    }
    .actions button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 12px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.3s;
    }
    .actions button:hover {
        background-color: #45a049;
    }
    

    @media (max-width: 600px) {
        .actions {
            flex-wrap: wrap;
            justify-content: center;
        }
        .actions button {
            flex: 1;
            padding: 4px;
            font-size: 10px;
        }
    }
    @media (max-width: 480px) {
        .actions button {
            padding: 6px;
            font-size: 10px;
        }
    }
    
</style>
<div class="form-container">
<form id="userForm" action="javascript:void(0);" method="POST" enctype="multipart/form-data">
    <div class="form-grid">
        <div class ="form-group">
            <label for="fullname">Fullname</label>
            <input type="text" id="fullname" name="fullname" required>
        </div>
        <div class="form-group">
            <label for="nickname">Nickname</label>
            <input type="text" id="nickname" name="nickname" autocomplete="off" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>
        </div>
        <div class="form-group">
    <label for="user_type">User Type</label>
    <select id="user_type" name="user_type" required>
        <option value="Admin">Admin</option>
        <option value="Cashier">Cashier</option>
        <option value="Inventory Admin">Inventory Admin</option>
        <option value="Procurement Admin">Procurement Admin</option>
        <option value="Dean">Dean</option>
        <option value="Faculty">Faculty</option>
    </select>   
</div>  <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="new-password" required>
        </div>
        <div class="form-group">
            <label for="created_at">Created At</label>
            <input type="datetime-local" id="created_at" name="created_at" required>
        </div>
        <div class="form-group">
            <label for="department">Department</label>
            <select id="department" name="department" required>
                <option value="Registrar">Registrar</option>
                <option value="Finance">Finance</option>
                <option value="IACEPO">IACEPO</option>
            </select>
        </div>
        <div class="form-group">
            <label for="user_image">User Image</label>
            <input type="file" id="user_image" name="user_image">
        </div>
        <div style="display: flex; gap: 45px; margin-top: 10px;">
    <button type="button" style="padding: 10px 12px; font-size: 12px;">Search</button>
    <button type="button" style="padding: 10px 12px; font-size: 12px;">Update</button>
    <button type="button" style="padding: 10px 12px; font-size: 12px;">Print</button>
    <button type="button" style="padding: 10px 12px; font-size: 12px;">Save</button>
        </div>
    </div>
</form>                                                                                      
<table class="user-table">  
    <thead>
        <tr>
            <th>ID</th>
            <th>User Image</th>
            <th>Fullname</th>
            <th>Nickname</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Password</th>
            <th>Created At</th>
            <th>Department</th>
        </tr>
    </thead>
    <tbody></tbody>

<script>
function Saveusers() {
    const formData = new FormData(document.getElementById('userForm'));

    fetch('save_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User added successfully!');
            location.reload(); // Refresh table after saving
        } else {
            alert('Failed to add user: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>


