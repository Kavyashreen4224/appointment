<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Reset */
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Roboto', sans-serif; }

        body {
            background: linear-gradient(135deg, #6C63FF, #00C6FF);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .landing-container {
            background: #fff;
            border-radius: 15px;
            padding: 50px 40px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 40px;
        }

        .buttons a {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            color: #fff;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-register { background: #28a745; }
        .btn-register:hover { background: #218838; }

        .btn-login { background: #007bff; }
        .btn-login:hover { background: #0056b3; }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-30px);}
            100% { opacity: 1; transform: translateY(0);}
        }

        /* Cards for features */
        .features {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .feature-card {
            background: #f5f5f5;
            border-radius: 15px;
            padding: 20px;
            width: 220px;
            margin: 15px;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-card h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 0.95rem;
            color: #555;
        }

        @media(max-width: 600px){
            .features { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <h1>Welcome to HealthCare Portal</h1>
        <p>Manage appointments, doctors, and patients easily. Multi-role & multi-hospital support included.</p>
        <div class="buttons">
            <a href="<?= site_url('register') ?>" class="btn-register">Register</a>
            <a href="<?= site_url('login') ?>" class="btn-login">Login</a>
        </div>

        <div class="features">
            <div class="feature-card">
                <h3>Manage Doctors</h3>
                <p>Add, edit, delete doctors with full profile and appointments management.</p>
            </div>
            <div class="feature-card">
                <h3>Manage Patients</h3>
                <p>Patient registration, visit history, health trends, and appointment tracking.</p>
            </div>
            <div class="feature-card">
                <h3>Appointments</h3>
                <p>Create, reschedule, cancel appointments with real-time validations.</p>
            </div>
            <div class="feature-card">
                <h3>Multi-role Access</h3>
                <p>SuperAdmin, Admin, Doctor, Patient dashboards with role-based access.</p>
            </div>
        </div>
    </div>
</body>
</html>
