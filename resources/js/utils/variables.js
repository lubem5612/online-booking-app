/*
  |
  | save variables and constants here
  | to be imported and used in the application
  |
 */

// responsive layout for mobile, tablet, && desktop
const MOBILE_WIDTH = 600;
const TABLET_WIDTH = 769;
export const screenWidth = {
    mobile: {
        max_width: MOBILE_WIDTH
    },
    tablet: {
        min_width: MOBILE_WIDTH,
        max_width: TABLET_WIDTH
    },
    laptop: {
        min_width: TABLET_WIDTH
    },
    isMobile: window.innerWidth < MOBILE_WIDTH,
    isTablet:
        window.innerWidth >= MOBILE_WIDTH || window.innerWidth <= TABLET_WIDTH,
    isLaptop: window.innerWidth > TABLET_WIDTH
};

//roles
export const ROLES = {
    superAdmin: 5,
    admin: 4,
    staff: 3,
    student: 2,
    visitor: 1,
};

export const getInitials = (first_name, last_name) => {
    let name = `${first_name} ${last_name}`;
    const firstTwoWords = name.split(' ').slice(0, 2).join(' ');
    return firstTwoWords.split(/\s/).reduce((response, word) => (response ? (response += '.' + word.slice(0, 1)) : (response = word.slice(0, 1))), '');
};

export const DAY_OF_WEEK = (day_number) => {
    let days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];
    return days[day_number]? days[day_number] : 'NULL';
}
