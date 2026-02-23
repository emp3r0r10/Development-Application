<?php
include("db_connect.php");

if (!isset($_SESSION['email']) || $_SESSION['email'] !== "john.smith@gmail.com") {
  http_response_code(403);
  exit("<h1>403 Forbidden</h1> You are not allowed to access this page.");
}

$loggedIn = false;
$username = "";
$profileImagePath = "uploads/default.png"; // default image

if (isset($_SESSION['email'])) {
  $loggedIn = true;
  $email = $_SESSION['email'];

  $sql = "SELECT firstname, lastname, username, profile_image FROM users WHERE email = 'john.smith@gmail.com'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];    
    $username = $row['username'];
    $profileImagePath = 'site_images/profile_images/' . $row['profile_image'];
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="Theme.js"></script>
</head>
<body>
  <div class="sidebar">
    <h2><a href="main.php"><img class="logo" src="site_images/logo.png" alt="logo"></a></h2>
    <ul>
      <li class="active"><a href="admin.php">Dashboard</a></li>
      <li><a href="update_products.php">Menu</a></li>
      <li><a href="#">Food Order</a></li>
      <li><a href="blog_updates.php">Blog Posts</a></li>
      <li><a href="profile.php">Settings</a></li>
    </ul>
    <div class="upgrade-box">
      <p>Upgrade your Account to get more benefits</p>
      <button>Upgrade</button>
    </div>
  </div>

  <div class="main-content">
    <div class="top-bar">
      <h1>Dashboard</h1>
        <div class="user-info">
          <h4><?php echo $firstname . ' ' . $lastname ?></h4>
          <img src="<?php echo $profileImagePath ?>" class="avatar" alt="Avatar">
        </div>
    </div>

    <div class="dashboard-grid">
      <!-- Summary Cards -->
      <div class="summary-tile">
        <div>
          <p class="label">Total Income</p>
          <h2>$12,890.00</h2>
        </div>
        <div>
          <p class="label">Income</p>
          <h3>$4,345.00 <span class="badge up">+15%</span></h3>
        </div>
        <div>
          <p class="label">Expense</p>
          <h3>$2,890.00 <span class="badge down">-10%</span></h3>
        </div>
      </div>

      <!-- Chart + Performance -->
      <div class="chart-performance">
        <div class="bar-chart-tile">
          <canvas id="summaryBarChart"></canvas>
        </div>
        <div class="performance-tile">
          <h3>Performance</h3>
          <div class="donut-wrap">
            <canvas id="performanceDonutChart" width="100" height="100"></canvas>
            <div class="donut-center">
              <p class="percent">+15%</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Stats -->
      <div class="order-summary-card">
        <div class="order-item">
          <div class="icon">✅</div>
          <div>
            <p class="order-label">Total Order Complete</p>
            <h3>2,678</h3>
          </div>
        </div>
        <div class="order-item">
          <div class="icon">✔️</div>
          <div>
            <p class="order-label">Total Order Delivered</p>
            <h3>1,234</h3>
          </div>
        </div>
        <div class="order-item">
          <div class="icon">⚠️</div>
          <div>
            <p class="order-label">Total Order Canceled</p>
            <h3>123</h3>
          </div>
        </div>
        <div class="order-item">
          <div class="icon">🕒</div>
          <div>
            <p class="order-label">Order Pending</p>
            <h3>432</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Rate & Popular Food -->
    <div class="two-column-row">
      <!-- Order Rate Chart -->
      <div class="card order-rate-card">
        <div class="card-header">
          <div class="text-section">
            <h3>Order Rate</h3>
            <p><span class="bold">Total:</span> 25,307 | <span class="bold">Target:</span> 3,982</p>
          </div>
          <select class="month-dropdown">
            <option>Month</option>
            <option>Week</option>
            <option>Year</option>
          </select>
        </div>
        <div class="chart-fixed">
          <canvas id="orderRateChart"></canvas>
        </div>
      </div>

      <!-- Popular Food Card -->
      <div class="card popular-food-card">
        <div class="card-header">
          <h3>Popular Food</h3>
          <select class="month-dropdown">
            <option>Month</option>
            <option>Week</option>
            <option>Year</option>
          </select>
        </div>
        <div class="food-chart-container">
          <canvas id="foodPieChart"></canvas>
          <div class="food-list">
            <div class="food-item">
              <div class="food-name">Pizza</div>
              <div class="food-bar"><div class="food-progress" style="width: 85%; background-color: #ff5722;"></div></div>
              <div class="food-count">2,450</div>
            </div>
            <div class="food-item">
              <div class="food-name">Burger</div>
              <div class="food-bar"><div class="food-progress" style="width: 70%; background-color: #4caf50;"></div></div>
              <div class="food-count">1,960</div>
            </div>
            <div class="food-item">
              <div class="food-name">Tacos</div>
              <div class="food-bar"><div class="food-progress" style="width: 60%; background-color: #2196f3;"></div></div>
              <div class="food-count">1,420</div>
            </div>
            <div class="food-item">
              <div class="food-name">Fried Chicken</div>
              <div class="food-bar"><div class="food-progress" style="width: 45%; background-color: #e91e63;"></div></div>
              <div class="food-count">980</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Activity & Tasks -->
    <div class="bottom-two-column">
      <!-- Activity Overview -->
      <div class="card activity-card">
        <div class="card-header">
          <h3>Activity Overview</h3>
          <select class="month-dropdown">
            <option>Today</option>
            <option>This Week</option>
            <option>This Month</option>
          </select>
        </div>
        <div class="activity-chart-container">
          <canvas id="activityChart"></canvas>
        </div>
        <div class="activity-details">
          <div class="activity-detail">
            <h4>Active Users</h4>
            <p>3,240</p>
          </div>
          <div class="activity-detail">
            <h4>New Signups</h4>
            <p>432</p>
          </div>
          <div class="activity-detail">
            <h4>Orders</h4>
            <p>1,120</p>
          </div>
        </div>
      </div>

      <!-- Tasks -->
      <div class="card task-card">
        <div class="card-header">
          <h3>Tasks</h3>
          <span class="task-count">932</span>
        </div>
        <div class="task-summary">
          <div class="task-item">
            <span class="task-icon">✅</span>
            <div class="task-info">
              <p class="task-title">Completed</p>
              <p class="task-number">738</p>
            </div>
          </div>
          <div class="task-item">
            <span class="task-icon">🕒</span>
            <div class="task-info">
              <p class="task-title">Pending</p>
              <p class="task-number">126</p>
            </div>
          </div>
          <div class="task-item">
            <span class="task-icon">❌</span>
            <div class="task-info">
              <p class="task-title">Canceled</p>
              <p class="task-number">68</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Chart Scripts -->
  <script>
const summaryCtx = document.getElementById('summaryBarChart').getContext('2d');
new Chart(summaryCtx, {
  type: 'bar',
  data: {
    labels: ['00:00', '', '', '', '', '', '', '', '', '', '', '23:59'],
    datasets: [{
      data: [100, 80, 70, 110, 130, 95, 125, 140, 110, 90, 100, 115],
      backgroundColor: ['#ffcc00', '#f44336', '#ffcc00', '#f44336', '#ffcc00', '#f44336', '#ffcc00', '#f44336', '#ffcc00', '#f44336', '#ffcc00', '#f44336'],
      borderRadius: 6
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      x: { ticks: { color: '#888' }, grid: { display: false } },
      y: { ticks: { color: '#888' }, grid: { color: '#eee' } }
    }
  }
});

const donutCtx = document.getElementById('performanceDonutChart').getContext('2d');
new Chart(donutCtx, {
  type: 'doughnut',
  data: {
    datasets: [{
      data: [160, 85],
      backgroundColor: ['#f4a300', '#eee'],
      borderWidth: 0
    }]
  },
  options: {
    responsive: true,
    rotation: -90,           // Start from top
    circumference: 180,      // Half circle (180 degrees)
    cutout: '70%',
    plugins: {
      legend: { display: false },
      tooltip: { enabled: false }
    }
  }
});
</script>

  <script>
    const ctx = document.getElementById('orderRateChart').getContext('2d');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
          {
            label: 'This Month',
            data: [90, 110, 85, 120, 100, 160, 210, 180, 200, 250, 230, 220],
            fill: true,
            backgroundColor: 'rgba(255, 165, 0, 0.4)',
            borderColor: '#FF9900',
            tension: 0.4,
            pointBackgroundColor: '#FF9900',
          },
          {
            label: 'Last Month',
            data: [70, 90, 60, 100, 80, 120, 170, 160, 180, 200, 190, 185],
            fill: true,
            backgroundColor: 'rgba(255, 50, 90, 0.3)',
            borderColor: '#FF3366',
            tension: 0.4,
            pointBackgroundColor: '#FF3366',
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#333',
              font: { size: 14, weight: 'bold' }
            }
          },
          tooltip: {
            backgroundColor: '#222',
            titleColor: '#fff',
            bodyColor: '#fff'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { color: '#333' },
            grid: { color: '#eee' }
          },
          x: {
            ticks: { color: '#333' },
            grid: { color: '#eee' }
          }
        }
      }
    });

    const foodCtx = document.getElementById('foodPieChart').getContext('2d');
    new Chart(foodCtx, {
      type: 'pie',
      data: {
        labels: ['Pizza', 'Burger', 'Tacos', 'Fried Chicken'],
        datasets: [{
          data: [2450, 1960, 1420, 980],
          backgroundColor: ['#ff5722', '#4caf50', '#2196f3', '#e91e63'],
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#444',
              font: { size: 13, weight: 'bold' }
            }
          }
        }
      }
    });

    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
      type: 'line',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'Activity',
          data: [120, 140, 130, 170, 180, 160, 200],
          fill: true,
          backgroundColor: 'rgba(76, 175, 80, 0.2)',
          borderColor: '#4caf50',
          tension: 0.4,
          pointBackgroundColor: '#4caf50',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#333',
            titleColor: '#fff',
            bodyColor: '#fff'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { color: '#444' },
            grid: { color: '#eee' }
          },
          x: {
            ticks: { color: '#444' },
            grid: { color: '#eee' }
          }
        }
      }
    });
  </script>
</body>
</html>
