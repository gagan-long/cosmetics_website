<?php
session_start();
include_once '../models/product_model.php'; // Include the ProductModel

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel(); // Instantiate the ProductModel
    }

    // Method to fetch all products
    public function listProducts() {
        try {
            $products = $this->productModel->getAllProducts();
            echo json_encode([
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error fetching products: ' . htmlspecialchars($e->getMessage()),
            ]);
        }
    }

    // Method to fetch a product by ID
    public function viewProduct($product_id) {
        try {
            if (empty($product_id)) throw new Exception("Invalid product ID.");
            
            $product = $this->productModel->getProductById($product_id);
            if ($product) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $product,
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Product not found.',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error fetching product: ' . htmlspecialchars($e->getMessage()),
            ]);
        }
    }

    // Method to add a new product
    public function addProduct() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Get form data and sanitize inputs
                if (!isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['image_url'])) {
                    throw new Exception("Missing required fields.");
                }
                
                $name = $_POST['name'];
                $description = $_POST['description'];
                floatval($_POST['price']);  // Ensure price is a float value.
                htmlspecialchars($_POST['image_url']);  // Sanitize image URL.

                if ($this->productModel->addProduct($name, htmlspecialchars($description), floatval($_POST['price']), htmlspecialchars($_POST['image_url']))) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Product added successfully!',
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error occurred while adding the product.',
                    ]);
                }
            } catch (Exception 	$e) { 
               echo json_encode([
                   'status' => 'error',
                   'message' => htmlspecialchars($e->getMessage()),
               ]);
           }
       }
   }

   // Method to update an existing product
   public function updateProduct($product_id) {
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           try {
               if (empty($product_id)) throw new Exception("Invalid product ID.");

               // Get updated form data and sanitize inputs
               if (!isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['image_url'])) {
                   throw new Exception("Missing required fields.");
               }

               if ($this->productModel->updateProduct(
                   intval($product_id),
                   htmlspecialchars($_POST['name']),
                   htmlspecialchars($_POST['description']),
                   floatval($_POST['price']),
                   htmlspecialchars($_POST['image_url'])
               )) {
                   echo json_encode([
                       'status' => 'success',
                       'message' => 'Product updated successfully!',
                   ]);
               } else {
                   echo json_encode([
                       'status' => 'error',
                       'message' => 'Error occurred while updating the product.',
                   ]);
               }
           } catch (Exception 	$e) { 
               echo json_encode([
                   'status' => 'error',
                   'message' => htmlspecialchars($e->getMessage()),
               ]);
           }
       }
   }

   // Method to delete a product
   public function deleteProduct($product_id) {
       try {
           if ($this->productModel->deleteProduct(intval($product_id))) { 
               echo json_encode([
                   'status' => 'success',
                   'message' => 'Product deleted successfully!',
               ]);
           } else { 
               echo json_encode([
                   'status' => 'error',
                   'message' => 'Error occurred while deleting the product.',
               ]);
           }
       } catch (Exception 	$e) { 
           echo json_encode([
               'status' => 'error',
               'message' => htmlspecialchars($e->getMessage()),
           ]);
       }
   }

   // Close the database connection when done
//    public function close() { 
//        this.productModel.closeConnection(); 
//    } 
}

// Example usage based on request type
// $productController = new ProductController();

// if (isset($_GET['action'])) { 
//    switch ($_GET['action']) { 
//        case 	'list':
//            this.productController.listProducts(); 
//            break; 
//        case 	'view':
//            if (isset($_GET['id'])) this.productController.viewProduct($_GET['id']); 
//            break; 
//        case 	'add':
//            this.productController.addProduct(); 
//            break; 
//        case 	'update':
//            if (isset($_GET['id'])) this.productController.updateProduct($_GET['id']); 
//            break; 
//        case 	'delete':
//            if (isset($_GET['id'])) this.productController.deleteProduct($_GET['id']); 
//            break; 
//    } 
// }

// $productController.close(); // Close the connection at the end

