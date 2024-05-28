import moment from "moment";

export function useTimeAgo() {
    const timeAgo = (value) => {
        if (value) {
            return moment(value).fromNow();
        }
        return "";
    };

    return { timeAgo };
}
