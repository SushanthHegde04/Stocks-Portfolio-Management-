function openNewPage(button) {
    const stockId = button.getAttribute("data-stock-id");
    const userId = button.getAttribute("data-user-id");
    const typetr=button.getAttribute("data-type");

    const content = `
        <div id="quantityBox" style="background-color: #333; color: white; padding: 10px; margin-top:20px;">
            <label for="quantityInput">Enter Quantity:</label>
            <input type="number" id="quantityInput" placeholder="Enter quantity" style="margin-right: 5px;">
            <button id="okButton" onclick="sendQuantity(${stockId}, ${userId},  '${typetr}')" style="background-color: #ff8c00; color: white; border: none; padding: 5px 10px; cursor: pointer;">OK</button>
        </div>
    `;

    // Append the generated content to the body
    document.body.innerHTML += content;
}

function sendQuantity(stockId, userId, typetr) {
    // Get the quantity from the input field
    alert ("eneter9");
    const quantityInput = document.getElementById("quantityInput");
    const quantity = quantityInput.value;

    if (quantity !== "") {
        // Create a new XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "buy.php", true);

        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                alert("XHR Status: " + xhr.status);
                alert("Response from server: " + xhr.responseText);

                
            }
        };

        // Send the data to the server
        const data = "action=" + typetr + "&stockId=" + stockId + "&userId=" + userId + "&quantity=" + quantity;
        alert("Sending data: " + data);

        xhr.send(data);

        // Remove the quantity input box from the body
        document.body.removeChild(document.getElementById("quantityBox"));
        setTimeout(function () {
            location.reload();
        }, 1000); 
        
   
    } else {
        alert("Please enter a valid quantity.");
    }
}
