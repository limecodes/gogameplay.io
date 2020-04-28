import { VALIDATE_PLATFORM, VALIDATE_CARRIER, VALIDATE_SEARCH } from './types';

export const validatePlatform = () => async dispatch => {
	dispatch({
		type: VALIDATE_PLATFORM
	});
};

export const validateCarrier = () => async dispatch => {
	dispatch({
		type: VALIDATE_CARRIER
	});
};

export const validateSearch = () => async dispatch => {
	dispatch({
		type: VALIDATE_SEARCH
	});
}