<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bin</title>
    <link rel="icon" type="image/x-icon" href="./img/rmuttlogo16x16.jpg">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/js/bootstrap.min.js">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <?php require 'template/header.php'; ?>
    <div class="container">
        <h1 class="mt-5 mb-4">Delete Items</h1>

        <ul id="itemList" class="list-group">
            <!-- Display items dynamically here -->
        </ul>

        <button class="btn btn-danger mt-3" onclick="deleteSelectedItems()">Delete Selected Items</button>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data
        const items = [{
                id: 1,
                name: 'Item 1'
            },
            {
                id: 2,
                name: 'Item 2'
            },
            {
                id: 3,
                name: 'Item 3'
            },
            // Add more items as needed
        ];

        // Function to display items on the page
        function displayItems() {
            const itemList = document.getElementById('itemList');
            itemList.innerHTML = '';

            items.forEach(item => {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.innerHTML = `<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="item${item.id}" value="${item.id}">
                            <label class="custom-control-label" for="item${item.id}">${item.name}</label>
                        </div>`;
                itemList.appendChild(li);
            });
        }

        // Function to delete selected items
        function deleteSelectedItems() {
            const selectedItems = [];

            // Get selected items
            items.forEach(item => {
                const checkbox = document.getElementById(`item${item.id}`);
                if (checkbox.checked) {
                    selectedItems.push(item.id);
                }
            });

            // Delete selected items from the array
            selectedItems.forEach(itemId => {
                const index = items.findIndex(item => item.id === itemId);
                if (index !== -1) {
                    items.splice(index, 1);
                }
            });

            // Refresh the displayed items
            displayItems();
        }

        // Initial display
        displayItems();
    </script>
</body>

</html>