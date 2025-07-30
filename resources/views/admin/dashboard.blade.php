<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hyper Drive</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .admin-dashboard {
            padding: 2rem;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(255, 19, 241, 0.1) 50%, rgba(0, 255, 255, 0.1) 100%);
            min-height: 100vh;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid rgba(255, 19, 241, 0.3);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .registrations-table {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        .table-header {
            background: rgba(255, 19, 241, 0.2);
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 19, 241, 0.3);
        }
        .table-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr auto;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 19, 241, 0.1);
            align-items: center;
        }
        .table-row:hover {
            background: rgba(255, 19, 241, 0.05);
        }
        .badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-warning {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        .badge-success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        .badge-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn-approve, .btn-reject {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-approve {
            background: rgba(40, 167, 69, 0.8);
            color: white;
        }
        .btn-approve:hover {
            background: rgba(40, 167, 69, 1);
            transform: translateY(-2px);
        }
        .btn-reject {
            background: rgba(220, 53, 69, 0.8);
            color: white;
        }
        .btn-reject:hover {
            background: rgba(220, 53, 69, 1);
            transform: translateY(-2px);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
        }
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.95);
            border: 1px solid rgba(255, 19, 241, 0.3);
            border-radius: 15px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            backdrop-filter: blur(15px);
        }
        .modal-header {
            margin-bottom: 1.5rem;
        }
        .modal-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .form-group textarea {
            width: 100%;
            min-height: 100px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 19, 241, 0.3);
            border-radius: 8px;
            padding: 0.8rem;
            color: var(--text-light);
            font-family: inherit;
        }
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }
        .btn-cancel {
            background: rgba(108, 117, 125, 0.8);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-cancel:hover {
            background: rgba(108, 117, 125, 1);
        }
        .btn-confirm {
            background: rgba(220, 53, 69, 0.8);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-confirm:hover {
            background: rgba(220, 53, 69, 1);
        }
        @media (max-width: 768px) {
            .table-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            .action-buttons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-dashboard">
        <div class="container">
            <h1 class="section-title">Admin Dashboard</h1>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Ümumi Başvuru</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Gözləyən</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['approved'] }}</div>
                    <div class="stat-label">Təsdiqlənən</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['rejected'] }}</div>
                    <div class="stat-label">Rədd Edilən</div>
                </div>
            </div>

            <!-- Registrations Table -->
            <div class="registrations-table">
                <div class="table-header">
                    <h2>Başvurular</h2>
                </div>
                
                <div class="table-row" style="font-weight: 600; color: var(--primary-color);">
                    <div>Ad Soyad</div>
                    <div>Email</div>
                    <div>Telefon</div>
                    <div>Avtomobil</div>
                    <div>Tarix</div>
                    <div>Status</div>
                    <div>Əməliyyatlar</div>
                </div>

                @foreach($registrations as $registration)
                <div class="table-row">
                    <div>{{ $registration->full_name }}</div>
                    <div>{{ $registration->email }}</div>
                    <div>{{ $registration->phone }}</div>
                    <div>{{ $registration->car_brand }} {{ $registration->car_model }}</div>
                    <div>{{ $registration->created_at->format('d.m.Y H:i') }}</div>
                    <div>{!! $registration->status_badge !!}</div>
                    <div class="action-buttons">
                        @if($registration->status === 'pending')
                            <button class="btn-approve" onclick="approveRegistration({{ $registration->id }})">
                                Təsdiqlə
                            </button>
                            <button class="btn-reject" onclick="showRejectModal({{ $registration->id }})">
                                Rədd Et
                            </button>
                        @else
                            <span style="color: var(--text-light); font-size: 0.8rem;">
                                {{ $registration->status === 'approved' ? 'Təsdiqləndi' : 'Rədd edildi' }}
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Başvuru Rədd Et</h3>
                <p style="color: var(--text-light);">Rədd etmə səbəbini yazın:</p>
            </div>
            <form id="rejectForm">
                <div class="form-group">
                    <label for="rejectNotes">Səbəb:</label>
                    <textarea id="rejectNotes" name="notes" required placeholder="Rədd etmə səbəbini yazın..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeRejectModal()">Ləğv Et</button>
                    <button type="submit" class="btn-confirm">Rədd Et</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentRegistrationId = null;

        function approveRegistration(id) {
            if (confirm('Bu başvurunu təsdiqləmək istədiyinizə əminsiniz?')) {
                fetch(`/admin/approve/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Xəta baş verdi!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Xəta baş verdi!');
                });
            }
        }

        function showRejectModal(id) {
            currentRegistrationId = id;
            document.getElementById('rejectModal').style.display = 'block';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('rejectNotes').value = '';
            currentRegistrationId = null;
        }

        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const notes = document.getElementById('rejectNotes').value;
            
            fetch(`/admin/reject/${currentRegistrationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ notes: notes })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeRejectModal();
                    location.reload();
                } else {
                    alert('Xəta baş verdi!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Xəta baş verdi!');
            });
        });

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
</body>
</html> 