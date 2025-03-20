// cwie-overview.js
// CWIE Overview Dashboard for Loei Rajabhat University

// Make sure we have access to React and ReactDOM from the global scope
const { React, ReactDOM } = window;
// Access Recharts from the global scope as loaded in your HTML
const { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, PieChart, Pie, Cell } = window.Recharts;

// Sample data - replace with actual data from your database
const facultyData = [
  { name: 'วิทยาการจัดการ', students: 120 },
  { name: 'วิทยาศาสตร์ฯ', students: 85 },
  { name: 'เทคโนโลยีอุตสาหกรรม', students: 65 },
  { name: 'มนุษยศาสตร์ฯ', students: 95 },
  { name: 'ครุศาสตร์', students: 55 }
];

const statusData = [
  { name: 'กำลังฝึกงาน', value: 185 },
  { name: 'สำเร็จการฝึกงาน', value: 235 }
];

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884d8'];
const STATUS_COLORS = ['#00C49F', '#0088FE'];

// CWIEOverview Component
const CWIEOverview = () => {
  const currentAcademicYear = new Date().getFullYear() + 543;
  
  return (
    <div className="cwie-dashboard">
      <div className="row">
        <div className="col-lg-12 mb-4">
          <div className="card">
            <div className="card-header bg-primary text-white">
              <h5 className="mb-0">ภาพรวมนักศึกษาสหกิจศึกษา ปีการศึกษา {currentAcademicYear}</h5>
            </div>
            <div className="card-body">
              <div className="row">
                <div className="col-lg-7">
                  <h6 className="text-center mb-3">จำนวนนักศึกษาแยกตามคณะ</h6>
                  <ResponsiveContainer width="100%" height={300}>
                    <BarChart data={facultyData} margin={{ top: 5, right: 20, bottom: 5, left: 0 }}>
                      <CartesianGrid strokeDasharray="3 3" />
                      <XAxis dataKey="name" />
                      <YAxis />
                      <Tooltip />
                      <Legend />
                      <Bar dataKey="students" name="จำนวนนักศึกษา" fill="#8884d8" />
                    </BarChart>
                  </ResponsiveContainer>
                </div>
                <div className="col-lg-5">
                  <h6 className="text-center mb-3">สถานะการฝึกงาน</h6>
                  <ResponsiveContainer width="100%" height={300}>
                    <PieChart>
                      <Pie
                        data={statusData}
                        cx="50%"
                        cy="50%"
                        labelLine={true}
                        outerRadius={100}
                        fill="#8884d8"
                        dataKey="value"
                        label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                      >
                        {statusData.map((entry, index) => (
                          <Cell key={`cell-${index}`} fill={STATUS_COLORS[index % STATUS_COLORS.length]} />
                        ))}
                      </Pie>
                      <Tooltip />
                    </PieChart>
                  </ResponsiveContainer>
                </div>
              </div>
              
              <div className="row mt-4">
                <div className="col-lg-3 col-md-6 mb-3">
                  <div className="card bg-primary text-white">
                    <div className="card-body">
                      <h6 className="card-title">นักศึกษาทั้งหมด</h6>
                      <h3 className="card-text">{facultyData.reduce((sum, item) => sum + item.students, 0)} คน</h3>
                    </div>
                  </div>
                </div>
                <div className="col-lg-3 col-md-6 mb-3">
                  <div className="card bg-success text-white">
                    <div className="card-body">
                      <h6 className="card-title">สถานประกอบการ</h6>
                      <h3 className="card-text">42 แห่ง</h3>
                    </div>
                  </div>
                </div>
                <div className="col-lg-3 col-md-6 mb-3">
                  <div className="card bg-info text-white">
                    <div className="card-body">
                      <h6 className="card-title">อาจารย์นิเทศ</h6>
                      <h3 className="card-text">28 คน</h3>
                    </div>
                  </div>
                </div>
                <div className="col-lg-3 col-md-6 mb-3">
                  <div className="card bg-warning text-dark">
                    <div className="card-body">
                      <h6 className="card-title">หลักสูตร CWIE</h6>
                      <h3 className="card-text">15 หลักสูตร</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

// Render the component to the container
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('cwie-overview-container');
  if (container && window.React && window.ReactDOM && window.Recharts) {
    ReactDOM.render(React.createElement(CWIEOverview), container);
  } else {
    console.error('Required libraries or container not found:', {
      container: !!container,
      React: !!window.React,
      ReactDOM: !!window.ReactDOM,
      Recharts: !!window.Recharts
    });
  }
});

// Add this script at the end of your JavaScript files or in a new file
document.addEventListener('DOMContentLoaded', function() {
  // Function to handle image loading errors
  function handleImageError(img) {
    // Set a default placeholder image when image fails to load
    img.onerror = null; // Prevent infinite loop
    img.src = 'images/placeholder.jpg'; // Update this path to an existing placeholder image
  }

  // Apply to all images in the document
  const images = document.querySelectorAll('img');
  images.forEach(img => {
    img.onerror = function() {
      handleImageError(this);
    };
  });

  // Special handling for owl carousel to avoid 404 errors
  if (typeof $.fn.owlCarousel !== 'undefined') {
    $('.owl-carousel').on('initialized.owl.carousel', function() {
      $(this).find('img').each(function() {
        $(this).on('error', function() {
          handleImageError(this);
        });
      });
    });
  }
});