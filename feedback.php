<?php
session_start();
include 'dbconnect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedbackType = $_POST['feedbackType'];
    $message = $_POST['message'];

    $stmt = $con->prepare("INSERT INTO feedback (name, email, feedback_type, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $feedbackType, $message);

    if ($stmt->execute()) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Retrieve testimonials and FAQs
$testimonials = [];
$faqs = [];

$result = $con->query("SELECT text, author FROM testimonials");
while ($row = $result->fetch_assoc()) {
    $testimonials[] = $row;
}

$result = $con->query("SELECT question, answer FROM faqs");
while ($row = $result->fetch_assoc()) {
    $faqs[] = $row;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Power Solutions - Feedback</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --accent-color: #FFC107;
            --background-color: #FFFFFF;
            --text-color: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 0 20px;
        }

        .navbar {
            background-color: var(--primary-color);
            color: var(--background-color);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar ul {
            display: flex;
            list-style: none;
        }

        .navbar ul li {
            margin-left: 20px;
        }

        .navbar a {
            color: var(--background-color);
            text-decoration: none;
            font-size: 18px;
        }

        .feedback-section {
            padding: 6rem 0 4rem 0;
            background-color: var(--background-color);
        }

        .feedback-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .feedback-form {
            flex: 1;
            min-width: 300px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .feedback-form h2 {
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group textarea {
            height: 150px;
        }

        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: var(--background-color);
            padding: 0.8rem 1.5rem;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            border-radius: 30px;
            text-decoration: none;
            transition: opacity 0.2s ease-in;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .testimonials {
            flex: 1;
            min-width: 300px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .testimonials h2 {
            margin-bottom: 1rem;
        }

        .testimonial-card {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .testimonial-card p {
            font-style: italic;
            margin-bottom: 0.5rem;
        }

        .testimonial-card .author {
            text-align: right;
            font-weight: bold;
        }

        .faq {
            flex-basis: 100%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .faq h2 {
            margin-bottom: 1rem;
        }

        .faq-item {
            margin-bottom: 1rem;
        }

        .faq-question {
            font-weight: bold;
            cursor: pointer;
        }

        .faq-answer {
            display: none;
            margin-top: 0.5rem;
            padding-left: 1rem;
        }

        .footer {
            background-color: var(--text-color);
            color: var(--background-color);
            text-align: center;
            padding: 2rem 0;
            margin-top: 2rem;
        }

        .footer a {
            color: var(--background-color);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .feedback-container {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    </head>

    <body>
        <nav class="navbar">
            <div class="container">
                <h1>Eco Power Solutions</h1>
                <ul>
                    <li><a href="Home.html">Home</a></li>
                    <li><a href="Donation.html">Donate</a></li>
                    <li><a href="Track.html">Track</a></li>
                    <li><a href="Impact.html">Impact</a></li>
                    <li><a href="Volunteer.html">Volunteer</a></li>
                    <li><a href="feedback.php">Feedback</a></li>
                    <li><a href="login.php">Logout</a></li>
                </ul>
            </div>
        </nav>

        <section id="feedback" class="feedback-section">
            <div class="container feedback-container">
                <div class="feedback-form">
                    <h2>Share Your Feedback</h2>
                    <form id="feedbackForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="feedbackType">Feedback Type</label>
                            <select id="feedbackType" name="feedbackType" required>
                                <option value="">Select an option</option>
                                <option value="suggestion">Suggestion</option>
                                <option value="complaint">Complaint</option>
                                <option value="praise">Praise</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn">Submit Feedback</button>
                    </form>
                </div>

                <div class="testimonials">
                    <h2>Recent Testimonials</h2>
                    <div id="testimonialList">
                        <!-- Testimonials will be inserted here -->
                    </div>
                </div>

                <div class="faq">
                    <h2>Frequently Asked Questions</h2>
                    <div id="faqList">
                        <!-- FAQ items will be inserted here -->
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Eco Power Solutions. All rights reserved.</p>
                <p>
                    <a href="#">Privacy Policy</a> |
                    <a href="#">Terms of Service</a> |
                    <a href="Feedback.html">Contact Us</a>
                </p>
            </div>
        </footer>
        <script>
            const testimonials = <?php echo json_encode($testimonials); ?>;
            const faqs = <?php echo json_encode($faqs); ?>;

            document.addEventListener('DOMContentLoaded', function () {
                populateTestimonials();
                populateFAQs();
                setupFeedbackForm();
            });

            function populateTestimonials() {
                const testimonialList = document.getElementById('testimonialList');
                testimonials.forEach(testimonial => {
                    const testimonialCard = document.createElement('div');
                    testimonialCard.classList.add('testimonial-card');
                    testimonialCard.innerHTML = `
                    <p>"${testimonial.text}"</p>
                    <div class="author">- ${testimonial.author}</div>
                `;
                    testimonialList.appendChild(testimonialCard);
                });
            }

            function populateFAQs() {
                const faqList = document.getElementById('faqList');
                faqs.forEach(faq => {
                    const faqItem = document.createElement('div');
                    faqItem.classList.add('faq-item');
                    faqItem.innerHTML = `
                    <div class="faq-question">${faq.question}</div>
                    <div class="faq-answer">${faq.answer}</div>
                `;
                    faqList.appendChild(faqItem);
                });

                document.querySelectorAll('.faq-question').forEach(question => {
                    question.addEventListener('click', function () {
                        this.nextElementSibling.style.display =
                            this.nextElementSibling.style.display === 'none' ? 'block' : 'none';
                    });
                });
            }

            function setupFeedbackForm() {
                const feedbackForm = document.getElementById('feedbackForm');
                feedbackForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const formData = new FormData(feedbackForm);

                    fetch('feedback.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            feedbackForm.reset();
                        })
                        .catch(error => console.error('Error:', error));
                });
            }
        </script>
    </body>

</html>