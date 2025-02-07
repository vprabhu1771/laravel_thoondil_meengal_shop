POST /api/reports
Content-Type: application/json

{
  "selectedTiming": "Morning",  // Optional, can be omitted
  "startDate": "2025-02-01",
  "endDate": "2025-02-28"
}

{
  "totalSales": 12000.50,
  "productOrders": [
    {
      "product_name": "Product A",
      "total_quantity": 50,
      "total_amount": 5000.00
    },
    {
      "product_name": "Product B",
      "total_quantity": 30,
      "total_amount": 3000.00
    }
  ]
}
