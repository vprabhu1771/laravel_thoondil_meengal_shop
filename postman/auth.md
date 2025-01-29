Here’s how you can make Postman requests for login, logout, registration, and getting user details using your Laravel API.

---

### **1. Register User**  
**Endpoint:**  
```
POST http://your-domain.com/api/v2/register
```
**Headers:**  
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json"
}
```
**Body (JSON):**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "device_name": "MyDevice"
}
```

**Response (Success):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2024-09-14T10:00:00Z",
    "updated_at": "2024-09-14T10:00:00Z"
  }
}
```

---

### **2. Login User**
**Endpoint:**  
```
POST http://your-domain.com/api/v2/login
```
**Headers:**  
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json"
}
```
**Body (JSON):**
```json
{
  "email": "john@example.com",
  "password": "password123",
  "device_name": "MyDevice"
}
```

**Response (Success - Token Received):**
```json
"1|Xyz123AbcTokenHere"
```

---

### **3. Get User Details**  
**Endpoint:**  
```
GET http://your-domain.com/api/v2/user
```
**Headers:**  
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer 1|Xyz123AbcTokenHere"
}
```

**Response (Success):**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "photo_path": "http://your-domain.com/storage/profile.jpg"
}
```

---

### **4. Logout User**  
**Endpoint:**  
```
POST http://your-domain.com/api/v2/logout
```
**Headers:**  
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer 1|Xyz123AbcTokenHere"
}
```

**Response (Success):**
```json
"Tokens are deleted"
```

---

### **Issues in Your Code:**
1. **Missing `use Exception;`**  
   - Your `catch (Exception $e)` will throw an error. Add `use Exception;` at the top.

2. **Fix Exception Handling for Logout:**  
   - The `catch (ValidationException $e)` in `logout()` should handle all exceptions instead.
   ```php
   catch (Exception $e) {
       return response()->json(['error' => 'Something went wrong'], 500);
   }
   ```

3. **Fix Image Path Handling in `getUser()`**  
   - If the user doesn't have a profile photo, this will break. Use:
   ```php
   $user->photo_path = $user->photo_path ? url('/storage/' . $user->photo_path) : null;
   ```

Test these requests in Postman and let me know if you encounter any issues! 🚀