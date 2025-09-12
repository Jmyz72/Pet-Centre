<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e2e8f0;
        }
        .field {
            margin-bottom: 15px;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        .field-label {
            font-weight: bold;
            color: #4b5563;
            margin-bottom: 5px;
        }
        .field-value {
            color: #1f2937;
        }
        .message-content {
            white-space: pre-wrap;
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
        <p>Pet Centre Website</p>
    </div>
    
    <div class="content">
        <div class="field">
            <div class="field-label">Name:</div>
            <div class="field-value">{{ $formData['name'] }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value">{{ $formData['email'] }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Phone:</div>
            <div class="field-value">{{ $formData['phone'] ?? 'Not provided' }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Subject:</div>
            <div class="field-value">{{ $formData['subject'] }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Message:</div>
            <div class="message-content">{{ $formData['message'] }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Submitted At:</div>
            <div class="field-value">{{ now()->format('F j, Y \a\t g:i A') }}</div>
        </div>
    </div>
</body>
</html>