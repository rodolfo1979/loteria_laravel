<template>
    <VChip color="warning" variant="outlined" size="x-large" class="text-h5">
        <span v-if="nextEventTime" >
            <VIcon color="success">mdi-party-popper</VIcon>
            Próximos Sorteos en {{ hours }}h : {{ minutes }}m : {{ seconds }}s
        </span>
        <span v-else>
            <VIcon color="error">mdi-emoticon-sad-outline</VIcon>
            Sin Sorteos Ahora
        </span>
    </VChip>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import moment from 'moment';

export default {
    props: {
        eventHours: { // Array de horas de eventos (ej: [14, 15, 19])
            type: Array,
            default: () => [14, 15, 19],
        },
    },
    setup(props) {
        const hours = ref(0);
        const minutes = ref(0);
        const seconds = ref(0);
        const nextEventTime = ref(null); // Aquí almacenamos el tiempo del próximo evento
        let interval;

        const findNextEventTime = () => {
            const now = moment();
            const todayEventTimes = props.eventHours.map((hour) =>
                moment().hour(hour).minute(0).second(0)
            );

            // Buscar la próxima hora de evento que no haya pasado aún hoy
            let nextEvent = todayEventTimes.find((time) => now.isBefore(time));

            // Si todas las horas ya pasaron hoy, tomamos la primera hora de mañana
            if (!nextEvent) {
                nextEvent = moment().add(1, 'day').hour(props.eventHours[0]).minute(0).second(0);
            }

            nextEventTime.value = nextEvent;
        };

        const calculateTimeRemaining = () => {
            const now = moment();

            if (nextEventTime.value) {
                const diff = moment.duration(nextEventTime.value.diff(now));

                hours.value = Math.floor(diff.asHours());
                minutes.value = diff.minutes();
                seconds.value = diff.seconds();
            }
        };

        onMounted(() => {
            findNextEventTime(); // Encuentra la próxima hora de evento al montarse
            calculateTimeRemaining(); // Calcula el tiempo restante
            interval = setInterval(calculateTimeRemaining, 1000); // Actualiza cada segundo
        });

        onBeforeUnmount(() => {
            clearInterval(interval); // Limpia el intervalo cuando se desmonta
        });

        return { hours, minutes, seconds, nextEventTime };
    },
};
</script>
