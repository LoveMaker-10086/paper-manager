<!-- resources/views/ccf_a_conferences.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCF 会议信息系统</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/paper_deadlines.css') }}">
    <script src="https://kit.fontawesome.com/4d5bcdb1c1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1>CCF 会议信息系统</h1>
    <div class="login-button">
        <button type="button" onclick="login()">Login</button>
    </div>
    <div class="logout-button">
        <button type="button" onclick="logout()">Logout</button>
    </div>
    <div class="archive-button">
        <button type="button" onclick="archive()">Archive</button>
    </div>
    <table>
        <thead>
            <tr>
                <th onclick="sortTable(0)"> 会议简称 <span class="sort-icon no-sort-icon"></span></th>
                <th onclick="sortTable(1)"> 会议类型 <span class="sort-icon no-sort-icon"></span></th>
                <th onclick="sortTable(2)"> CCF分级 <span class="sort-icon no-sort-icon"></span></th>
                <th onclick="sortTable(3)"> 会议日期 <span class="sort-icon no-sort-icon"></span></th>
                <th onclick="sortTable(4)"> 截稿日期 <span class="sort-icon no-sort-icon"></span></th>
                <th onclick="sortTable(5)"> 倒计时 <span class="sort-icon no-sort-icon"></span></th>
                <th onclick="sortTable(6)"> 会议地点 <span class="sort-icon no-sort-icon"></span></th>
                @if ($message == "Login successful")
                    <th> 收藏 </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($conferences as $conference)
                <tr>
                    <td><a href="{{ $conference->website }}">{{ $conference->name }}</a></td>
                    <td>{{ $conference->type }}</td>
                    <td>{{ $conference->CCF }}</td>
                    <td>{{ $conference->date }}</td>
                    <td>{{ $conference->submission_deadline }}</td>
                    <td id="countdown-{{ $conference->id }}"></td>
                    <td>{{ $conference->place }}</td>
                    @if ($message == "Login successful")
                        @if (in_array($conference->id, $archivedId))
                            <td id="star-{{ $conference->id }}" onclick="toggleStar({{ $conference->id }})">
                                <i class="fa-solid fa-star" style="color: orange;"></i>
                            </td>
                        @else 
                            <td id="star-{{ $conference->id }}" onclick="toggleStar({{ $conference->id }})">
                                <i class="fa-solid fa-star" style="color: black;"></i>
                            </td>
                        @endif
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <form id="logout-form" action="logout" method="post">
        @csrf <!-- Laravel 中用于防止跨站请求伪造攻击的令牌 -->
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateCountdowns();
            setInterval(updateCountdowns, 1000); // 每秒更新一次

            function updateCountdowns() {
                const now = new Date();
                @foreach ($conferences as $conference)
                    const deadline_{{ $conference->id }} = new Date('{{ \Carbon\Carbon::parse($conference->submission_deadline)->format('Y-m-d\TH:i:s') }}');
                    const countdownElement_{{ $conference->id }} = document.getElementById('countdown-{{ $conference->id }}');
                    const secondsRemaining_{{ $conference->id }} = Math.floor((deadline_{{ $conference->id }} - now) / 1000);

                    if (secondsRemaining_{{ $conference->id }} <= 0) {
                        countdownElement_{{ $conference->id }}.textContent = '已截稿';
                    } else {
                        countdownElement_{{ $conference->id }}.textContent = formatCountdown(secondsRemaining_{{ $conference->id }});
                    }
                @endforeach
            }
        });

        function formatCountdown(seconds) {
            const days = Math.floor(seconds / 86400);
            const hours = Math.floor((seconds % 86400) / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;

            const daysPart = days > 0 ? `${padZero(days)} D ` : '';
            const hoursPart = hours > 0 ? `${padZero(hours)} H ` : '';
            const minutesPart = minutes > 0 ? `${padZero(minutes)} M ` : '';
            const secondsPart = padZero(remainingSeconds) + ' S';

            return `${daysPart}${hoursPart}${minutesPart}${secondsPart}`;
        }

        function padZero(number) {
            return number < 10 ? '0' + number : number;
        }

        function sortTable(columnIndex) {
            const table = document.querySelector('table');
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const sortIcon = table.querySelector(`th:nth-child(${columnIndex + 1}) .sort-icon`);

            if (!sortIcon.classList.contains('asc')) {
                // Initial state, sort in ascending order
                rows.sort((a, b) => {
                    const aValue = a.children[columnIndex].textContent.trim();
                    const bValue = b.children[columnIndex].textContent.trim();
                    return aValue.localeCompare(bValue);
                });
                resetSortIcons();
                sortIcon.classList.add('asc');
            } else if (sortIcon.classList.contains('asc')) {
                // Clicked once, sort in descending order
                rows.sort((a, b) => {
                    const aValue = a.children[columnIndex].textContent.trim();
                    const bValue = b.children[columnIndex].textContent.trim();
                    return bValue.localeCompare(aValue);
                });
                resetSortIcons();
                sortIcon.classList.add('desc');
            }

            table.querySelector('tbody').innerHTML = '';
            rows.forEach(row => table.querySelector('tbody').appendChild(row));
        }

        function resetSortIcons() {
            const icons = document.querySelectorAll('.sort-icon');
            icons.forEach(icon => {
                icon.classList.remove('asc', 'desc', 'no-sort-icon');
            });
        }

        function toggleStar(conferenceId) {
            const starIcon = document.getElementById(`star-${conferenceId}`).querySelector('.fa-star');
            // starIcon.style.color = starIcon.style.color === 'orange' ? 'black' : 'orange';
            if (starIcon.style.color === 'orange') {
                starIcon.style.color = 'black';
                dislike("{{ $username }}", conferenceId);
            }
            else if (starIcon.style.color === 'black') {
                starIcon.style.color = 'orange';
                like("{{ $username }}", conferenceId);
            }
        }

        function login() {
            window.location.href = 'login';
        }

        function logout() {
            var form = document.getElementById('logout-form');
            form.submit();
        }

        function archive() {
            window.location.href = 'archive';
        }

        function like(username, conferenceId) {
            const data = {
                'username': username,
                'conferenceId': conferenceId
            };

            // 发送 POST 请求
            axios.post('like', data);
        }

        function dislike(username, conferenceId) {
            const data = {
                'username': username,
                'conferenceId': conferenceId
            };

            // 发送 POST 请求
            axios.post('dislike', data);
        }

        @if($message == "Login successful")
            document.querySelector('.login-button').style.display = 'none';
            document.querySelector('.logout-button').style.display = 'inline-block';
            document.querySelector('.archive-button').style.display = 'inline-block';
            alert("{{ $message }}");
        @endif

        @if($message == "Logout successful")
            document.querySelector('.login-button').style.display = 'block';
            document.querySelector('.logout-button').style.display = 'none';
            alert("{{ $message }}");
        @endif
    </script>
</body>
</html>