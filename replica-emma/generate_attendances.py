from datetime import datetime, timedelta
import random

# ✅ Employee ID yang akan digenerate attendancenya
employee_ids = [54, 65, 67, 68]

# ✅ Tanggal mulai dan akhir
start_date = datetime.strptime("2025-04-01", "%Y-%m-%d")
end_date = datetime.strptime("2025-07-01", "%Y-%m-%d")

# ✅ Buat daftar tanggal (tanpa hari Minggu)
dates = []
current = start_date
while current <= end_date:
    if current.weekday() != 6:  # 6 = Minggu
        dates.append(current)
    current += timedelta(days=1)

# Fungsi clock in & status
def generate_clock_in():
    offset = random.choice(range(-15, 45))  # -15 s/d +45 menit dari jam 8
    base = datetime.strptime("08:00", "%H:%M")
    time = base + timedelta(minutes=offset)
    status = "late" if offset > 15 else "ontime"
    return time, status

# Fungsi clock out & status
def generate_clock_out():
    offset = random.choice(range(-90, 90))  # +/- dari jam 17:00
    base = datetime.strptime("17:00", "%H:%M")
    time = base + timedelta(minutes=offset)
    if time.hour < 16 or (time.hour == 16 and time.minute < 0):
        status = "early"
    elif time.hour >= 17 and time.minute > 0:
        status = "late"
    else:
        status = "ontime"
    return time, status

# Format jam ke string
def format_time(dt_obj):
    return dt_obj.strftime("%H:%M:%S")

# Generate datetime acak untuk created_at dan updated_at
def generate_timestamp(base_date):
    random_time = timedelta(
        hours=random.randint(1, 10),
        minutes=random.randint(0, 59),
        seconds=random.randint(0, 59)
    )
    full_dt = base_date + random_time
    return full_dt.strftime("%Y-%m-%d %H:%M:%S")

# Buat data attendance
rows = []
for emp_id in employee_ids:
    for date in dates:
        # Clock in & out
        clock_in_dt, cin_status = generate_clock_in()
        clock_out_dt, cout_status = generate_clock_out()

        # Pastikan clock_out lebih dari clock_in
        if clock_out_dt <= clock_in_dt:
            clock_out_dt = clock_in_dt + timedelta(hours=8)

        # Hitung durasi kerja (dalam menit)
        duration = int((clock_out_dt - clock_in_dt).total_seconds() // 60)

        # Waktu created_at dan updated_at
        base_date = datetime.combine(date.date(), datetime.min.time())
        created_at = generate_timestamp(base_date)
        updated_at = generate_timestamp(base_date)

        # Baris data SQL
        row = f"({emp_id}, '{date.date()}', '{format_time(clock_in_dt)}', '{format_time(clock_out_dt)}', '{cin_status}', '{cout_status}', {duration}, '{created_at}', '{updated_at}')"
        rows.append(row)

# Gabungkan jadi query SQL
query = (
    "INSERT INTO `attendances` "
    "(`employee_id`, `date`, `clock_in`, `clock_out`, `clock_in_status`, `clock_out_status`, `work_duration`, `created_at`, `updated_at`)\nVALUES\n"
    + ",\n".join(rows) +
    ";"
)

# Simpan ke file
with open("insert_attendances_apr_to_jul.sql", "w") as f:
    f.write(query)

print("✅ File 'insert_attendances_apr_to_jul.sql' berhasil dibuat.")
