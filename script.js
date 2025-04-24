document.addEventListener("DOMContentLoaded", function () {
    // -----Trip 
    if (window.location.pathname.includes("trip.html")) {
        document.getElementById("add-trip").addEventListener("click", function () {
            let tripName = document.getElementById("trip-name").value;
            let startDate = document.getElementById("start-date").value;
            let endDate = document.getElementById("end-date").value;
            let duration = document.getElementById("duration").value;
            let people = document.getElementById("people").value;

            if (!tripName || !startDate || !endDate || !people) {
                alert("Fill all the fields!");
                return;
            }

            addTrip(tripName, startDate, endDate, duration, people);
            clearForm();
        });

        function addTrip(name, start, end, duration, people) {
            const tripList = document.getElementById("trip-table");
            const row = document.createElement("tr");

            const nameCell = document.createElement("td");
            nameCell.textContent = name;
            row.appendChild(nameCell);

            const dateCell = document.createElement("td");
            dateCell.textContent = `${start} to ${end}`;
            row.appendChild(dateCell);

            const durationCell = document.createElement("td");
            durationCell.textContent = `${duration} day(s)`;
            row.appendChild(durationCell);

            const peopleCell = document.createElement("td");
            peopleCell.textContent = people;
            row.appendChild(peopleCell);

            const deleteCell = document.createElement("td");
            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Delete";
            deleteButton.classList.add("delete-btn");

            deleteButton.addEventListener("click", function () {
                deleteTrip(row);
            });

            deleteCell.appendChild(deleteButton);
            row.appendChild(deleteCell);

            tripList.appendChild(row);
        }

        function deleteTrip(row) {
            row.remove();
        }

        function clearForm() {
            document.getElementById("trip-name").value = "";
            document.getElementById("start-date").value = "";
            document.getElementById("end-date").value = "";
            document.getElementById("duration").value = "";
            document.getElementById("people").value = "";
        }

        document.getElementById("end-date").addEventListener("change", function () {
            let startDate = new Date(document.getElementById("start-date").value);
            let endDate = new Date(document.getElementById("end-date").value);

            if (startDate && endDate) {
                let timeDiff = endDate - startDate;
                let dayDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                document.getElementById("duration").value = dayDiff;
            }
        });
    }
///-------expense
   if (window.location.pathname.includes("expense.html")) {
    fetchExpenses();

    document.getElementById("budget-form").addEventListener("submit", function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value;
        const amount = parseFloat(document.getElementById("amount").value);
        const category = document.getElementById("category").value;
        const date = document.getElementById("date").value;

        if (!name || !amount || !category || !date) {
            alert("Please fill in all fields.");
            return;
        }

        const formData = new FormData();
        formData.append("name", name);
        formData.append("amount", amount);
        formData.append("category", category);
        formData.append("date", date);

        fetch("expense.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                fetchExpenses(); // Refresh table after adding
                clearForm();
            } else {
                alert("Failed to add expense.");
            }
        });
    });
}

function fetchExpenses() {
    fetch("expense.php")
    .then(res => res.json())
    .then(data => {
        const table = document.getElementById("expense-table");
        table.innerHTML = "";
        let total = 0;

        data.forEach(exp => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${exp.description}</td>
                <td>${parseFloat(exp.amount).toFixed(2)} BDT</td>
                <td>${exp.category}</td>
                <td>${exp.date}</td>
                <td><button class="delete-btn" data-id="${exp.id}">Delete</button></td>
            `;

            row.querySelector("button").addEventListener("click", function () {
                deleteExpense(exp.id);
            });

            table.appendChild(row);
            total += parseFloat(exp.amount);
        });

        document.getElementById("total").textContent = total.toFixed(2);
    });
}

function deleteExpense(id) {
    fetch(`expense.php?delete=${id}`)
    .then(() => fetchExpenses());
}

function clearForm() {
    document.getElementById("name").value = "";
    document.getElementById("amount").value = "";
    document.getElementById("category").value = "";
    document.getElementById("date").value = "";
}

    
    
    // -----Map 
    if (window.location.pathname.includes("map.html")) {
        var map = L.map('map').setView([22.3569, 91.7832], 10);

        var mapTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        });
        mapTile.addTo(map);
    }

    // -----To-Do Notification
    if (window.location.pathname.includes("planner.html")) {
        function checkTodosForNotification() {
            const rows = document.querySelectorAll("#todo-table tr");

            rows.forEach(row => {
                const deadlineCell = row.querySelector(".deadline");
                if (deadlineCell) {
                    const deadlineTime = new Date(deadlineCell.textContent.trim());
                    const currentTime = new Date();
                    const timeDiff = deadlineTime - currentTime;

                    if (timeDiff > 0 && timeDiff <= 6 * 60 * 60 * 1000) {
                        const taskName = row.querySelector(".task-name")?.textContent || "A task";
                        alert(`⏰ Reminder: "${taskName}" is due in less than 6 hours!`);
                    }
                }
            });
        }

        // Run it once on load
        checkTodosForNotification();

        // Check every 5 minutes
        setInterval(checkTodosForNotification, 5 * 60 * 1000);
    }
});