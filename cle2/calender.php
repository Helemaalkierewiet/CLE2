<?php
require_once 'includes/database.php';

/** @var mysqli $db */

// Fetch afspraken uit de database
$query = "SELECT * FROM calender_apps";
$result = mysqli_query($db, $query);

// Zet de afspraken om in het juiste formaat voor  het calender scrips xoxox i love oai
$calender_apps = [];
while ($row = mysqli_fetch_assoc($result)) {
    $calender_apps[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraken Kalender</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,500;1,800&display=swap"
</head>
<body>

<header class="screenshot-2023-12-11-135019-1-parent">
    <img
            class="screenshot-2023-12-11-135019-1"
            loading="eager"
            alt=""
            src="images/logo_144.png"
    />

    <div class="frame-parent">
        <div class="vector-parent">
            <img
                    class="frame-child"
                    alt=""
                    src="./public/rectangle-14.svg"
            />

            <a
                    class="home"
                    href="index.php"
                    target="_blank"
            >Home</a
            >
        </div>
        <div class="vector-group">
            <img
                    class="frame-item"
                    alt=""
                    src="./public/rectangle-15.svg"
            />

            <a
                    class="hoe-ziet-een"
                    href="https://boerderijweidelicht.nl/een-dag-op/"
                    target="_blank"
            >Hoe ziet een dag eruit</a
            >
        </div>
        <div class="vector-container">
            <img
                    class="frame-inner"
                    alt=""
                    src="./public/rectangle-16.svg"
            />

            <a
                    class="fotos"
                    href="https://www.figma.com/proto/OfRFP7CCgVH9oy64zSfQ4F/Untitled?type=design&node-id=21-43&t=5shvpIsrMsa1bECF-0&scaling=min-zoom&page-id=0%3A1"
                    target="_blank"
            >Foto´s</a
            >
        </div>
        <div class="frame-div">
            <img
                    class="rectangle-icon"
                    alt=""
                    src="./public/rectangle-18.svg"
            />

            <a
                    class="afspraak-maken"
                    href="calender.php"
                    target="_blank"
            >Afspraak maken</a
            >
        </div>
        <div class="vector-parent1">
            <img
                    class="frame-child1"
                    alt=""
                    src="./public/rectangle-17.svg"
            />

            <a
                    class="contact-locatie"
                    href="#divcontact"
                    target="_blank"
            >Contact & Locatie</a
            >
        </div>
        <button class="frame-button">
            <img
                    class="frame-child2"
                    alt=""
                    src="./public/rectangle-19.svg"
            />

            <a
                    class="login"
                    href="login.php"
                    target="_blank"
            >Login</a
            >
        </button>
    </div>
</header>

<div id="calendar"></div>

<!-- Voeg een Xtra modal toe-->
<div id="timeSlotModal" title="Kies een tijdslot">
    <p>Kies een tijdslot:</p>
    <form>
        <label><input type="radio" name="timeSlot" value="1"> 09:00 - 11:00</label>
        <label><input type="radio" name="timeSlot" value="2"> 13:30 - 15:30</label>
    </form>
</div>a

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'month',
            editable: true,
            events: <?php echo json_encode($calender_apps); ?>,
            selectable: false, // Zet FullCalendar-selectie uit
            eventClick: function (event) {
                if (confirm('Wil je deze afspraak verwijderen?')) {
                    $.ajax({
                        url: 'delete_appointment.php',
                        type: 'POST',
                        data: { id: event.id },
                        success: function () {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                        }
                    });
                }
            },
            eventRender: function(event, element) {
                element.find('.fc-title').html(event.title);
            }
        });

        // Click calender == onclick functie
        $('#calendar').on('click', '.fc-day', function (e) {
            e.preventDefault();
            showModal(moment($(this).data('date')));
        });
    });

    function showModal(selectedDate) {
        $("#timeSlotModal").dialog({
            modal: true,
            buttons: {
                "OK": function () {
                    var selectedTimeSlot = $("input[name='timeSlot']:checked").val();
                    if (selectedTimeSlot) {
                        var title = getTimeSlotTitle(selectedTimeSlot);
                        var eventData = {
                            title: title,
                            start: selectedDate.format(),
                            end: selectedDate.clone().add(2, 'hours').format()
                        };

                        // Controleer of het tijdslot beschikbaar is
                        isTimeSlotAvailable(eventData.start, eventData.end)
                            .then(function (isAvailable) {
                                if (isAvailable) {
                                    $('#calendar').fullCalendar('renderEvent', eventData, true);
                                    addAppointmentToDatabase(eventData);
                                    $(this).dialog("close");
                                } else {
                                    alert('Dit tijdslot is al geboekt. Kies een ander tijdslot.');
                                }
                            })
                            .catch(function (error) {
                                console.error("Error checking availability:", error);
                            });
                    }
                },
                "Annuleren": function () {
                    $(this).dialog("close");
                }
            }
        });
    }

    function getTimeSlotTitle(selectedTimeSlot) {
        // Voeg hier de logica toe om de juiste tekst voor elk tijdslot te bepalen
        if (selectedTimeSlot === '1') {
            return 'Tijdslot 1 (09:00 - 11:00)';
        } else if (selectedTimeSlot === '2') {
            return 'Tijdslot 2 (13:30 - 15:30)';
        } else {
            return 'Onbekend tijdslot';
        }
    }

    function isTimeSlotAvailable(startTime, endTime) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                type: 'POST',
                url: 'check_availability.php',
                data: { startTime: startTime, endTime: endTime },
                success: function (response) {
                    resolve(response === 'available');
                },
                error: function () {
                    reject(false);
                }
            });
        });
    }

    function addAppointmentToDatabase(eventData) {
        console.log("Sending data to server:", eventData); // Voeg deze regel toe voor debugging
        $.ajax({
            type: 'POST',
            url: 'add_appointment.php',
            data: {
                start: eventData.start,
                end: eventData.end,
                timeSlot: eventData.timeSlot
            },
            success: function (response) {
                console.log("Server response:", response); // Voeg deze regel toe voor debugging
                if (response !== 'Error') {
                    eventData.id = response;
                } else {
                    alert('Er is een fout opgetreden bij het toevoegen van de afspraak aan de database.');
                }
            }
        });
    }
</script>

<section class="rectangle-wrapper">
    <div class="rectangle">
        <div class="rectangle-child"></div>
        <div class="rectangle-inner">
            <div class="frame-group">
                <div class="text-parent" id="divcontact">
                    <div class="text6">
                        <div class="text-group">
                            <div class="text7">
                                <i class="contact">Contact</i>
                                <div class="line">
                                    <div class="whatsapp-logo-frame">
                                        <img
                                                class="whatsapp-logo-1"
                                                loading="eager"
                                                alt=""
                                                src="images/whatsapp%20logo.png"
                                        />

                                        <div class="whatsapp-06-">
                                            whatsapp: 06 - 27 51 09 11
                                        </div>
                                    </div>
                                    <div class="whatsapp-logo-frame1">
                                        <img
                                                class="telefoon-logo-1"
                                                loading="eager"
                                                alt=""
                                                src="images/telefoon%20logo.jpg"
                                        />

                                        <div class="div">015-3809999</div>
                                    </div>
                                </div>
                            </div>
                            <div class="locatie-logo-frame">
                                <img
                                        class="locatie-logo-1"
                                        loading="eager"
                                        alt=""
                                        src="images/locatie%20logo.jpg"
                                />

                                <div class="oostveenseweg-7-2636">
                                    Oostveenseweg 7, 2636 EC Schipluiden
                                </div>
                            </div>
                        </div>
                        <div class="mail-logo-frame">
                            <img
                                    class="mail-logo-1"
                                    loading="eager"
                                    alt=""
                                    src="images/mail%20logo.jpg"
                            />

                            <div class="infoboerderijweidelichtnl">
                                info@boerderijweidelicht.nl
                            </div>
                        </div>
                    </div>
                    <div class="locatie-parent">
                        <i class="locatie">Locatie</i>
                        <div class="locatieboederij-1-wrapper">
                            <img
                                    class="locatieboederij-1-icon"
                                    loading="eager"
                                    alt=""
                                    src="images/locatieboederij.png"
                            />
                        </div>
                    </div>
                </div>
                <div class="nieuws-frame">
                    <i class="nieuws">Nieuws</i>
                    <div class="de-keuken-het-container">
                  <span>
                    <p class="de-keuken-het">
                      De keuken: Het middelpunt van een huishouden, ook bij ons!
                    </p>
                    <p class="blank-line14">&nbsp;</p>
                    <p class="en-toen-was">
                      En toen was er ineens.... een kalfje!
                    </p>
                    <p class="blank-line15">&nbsp;</p>
                    <p class="heel-blij-met">Heel blij met de duo-fiets!</p>
                  </span>
                    </div>
                </div>
            </div>
        </div>
        <footer class="line-element">
            <div class="copyright-frame"></div>
            <div class="copyright-2019-all-rights-re-wrapper">
                <div class="copyright-2019">
                    Copyright 2019 | All Rights Reserved by Boerderij Weidelicht
                </div>
            </div>
        </footer>
    </div>
</section>

</body>
</html>