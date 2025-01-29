Here are the Postman request details for your `OrderController`:

---

### **1. Get Orders List (`index` method)**
**Endpoint:**  
`GET /api/v2/orders`

**Headers:**
- `Authorization: Bearer YOUR_ACCESS_TOKEN`
- `Accept: application/json`

**Response (Success):**
```json
{
    "timing_counts": {
        "All": 5,
        "Morning": 2,
        "Afternoon": 1,
        "Evening": 2
    },
    "data": [
        {
            "id": 1,
            "customer_name": "John Doe",
            "timings": "Morning",
            "total_amount": 150.00,
            "created_at": "2024-08-30T10:30:00",
            "updated_at": "2024-08-30T10:30:00"
        }
    ]
}
```

---

### **2. Create a New Order (`store` method)**
**Endpoint:**  
`POST /api/v2/orders`

**Headers:**
- `Authorization: Bearer YOUR_ACCESS_TOKEN`
- `Accept: application/json`
- `Content-Type: application/json`

**Body (JSON):**
```json
{
    "order_items": [
        {
            "product_id": 1,
            "qty": 2,
            "unit_price": 50
        },
        {
            "product_id": 2,
            "qty": 1,
            "unit_price": 100
        }
    ],
    "payment_method": "Credit Card"
}
```

**Response (Success):**
```json
{
    "data": {
        "id": 10,
        "user_id": 3,
        "total_amount": 200,
        "order_status": "pending",
        "payment_method": "Credit Card",
        "created_at": "2024-08-30T11:00:00",
        "updated_at": "2024-08-30T11:00:00",
        "order_items": [
            {
                "id": 1,
                "order_id": 10,
                "product_id": 1,
                "qty": 2,
                "unit_price": 50,
                "amount": 100,
                "discount": 0
            },
            {
                "id": 2,
                "order_id": 10,
                "product_id": 2,
                "qty": 1,
                "unit_price": 100,
                "amount": 100,
                "discount": 0
            }
        ]
    }
}
```

---

### **3. Get Single Order (`show` method)**
**Endpoint:**  
`GET /api/v2/orders/{order_id}`

**Headers:**
- `Authorization: Bearer YOUR_ACCESS_TOKEN`
- `Accept: application/json`

**Response (Success):**
```json
{
    "data": {
        "id": 10,
        "user_id": 3,
        "total_amount": 200,
        "order_status": "pending",
        "payment_method": "Credit Card",
        "created_at": "2024-08-30T11:00:00",
        "updated_at": "2024-08-30T11:00:00",
        "order_items": [
            {
                "id": 1,
                "order_id": 10,
                "product_id": 1,
                "qty": 2,
                "unit_price": 50,
                "amount": 100,
                "discount": 0
            },
            {
                "id": 2,
                "order_id": 10,
                "product_id": 2,
                "qty": 1,
                "unit_price": 100,
                "amount": 100,
                "discount": 0
            }
        ]
    }
}
```

---

### **4. Delete an Order (`destroy` method)**
**Endpoint:**  
`DELETE /api/v2/orders/{order_id}`

**Headers:**
- `Authorization: Bearer YOUR_ACCESS_TOKEN`
- `Accept: application/json`

**Response (Success):**
```json
{
    "message": "Order deleted successfully"
}
```

---

### **Notes**
- Replace `YOUR_ACCESS_TOKEN` with a valid token from your login request.
- Replace `{order_id}` with the actual order ID.
- If you get a validation error, ensure the request body contains correct data.

Would you like to add an update function for modifying orders? 🚀