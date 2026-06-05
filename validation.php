<?php

// Validate order item form data before inserting or updating database records.
function validateOrderInput($itemName, $quantity, $price, $orderDate, $status)
{
    $errors = [];
    $itemName = trim($itemName);
    $allowedStatuses = ["Pending", "Processed", "Cancelled"];

    // Item name must be present and must not be too short or too long.
    if ($itemName === "") {
        $errors[] = "Item name is required.";
    } elseif (strlen($itemName) < 2 || strlen($itemName) > 100) {
        $errors[] = "Item name must be between 2 and 100 characters.";
    }

    // Quantity should be a positive whole number.
    if ($quantity === false || $quantity === null || $quantity <= 0) {
        $errors[] = "Quantity must be a whole number greater than 0.";
    }

    // Price should be a positive number within a reasonable limit.
    if ($price === false || $price === null || $price <= 0) {
        $errors[] = "Price must be a number greater than 0.";
    } elseif ($price > 9999.99) {
        $errors[] = "Price must be less than 10000.";
    }

    if ($orderDate === "") {
        $errors[] = "Order date is required.";
    } else {
        $date = DateTime::createFromFormat("Y-m-d", $orderDate);
        if (!$date || $date->format("Y-m-d") !== $orderDate) {
            $errors[] = "Order date must be a valid date.";
        }
    }

    if (!in_array($status, $allowedStatuses, true)) {
        $errors[] = "Status must be Pending, Processed, or Cancelled.";
    }

    return $errors;
}

// Convert validation errors into one readable message for the page.
function getFirstValidationMessage($errors)
{
    return empty($errors) ? "" : implode(" ", $errors);
}

?>
