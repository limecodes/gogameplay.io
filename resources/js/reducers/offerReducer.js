import { FETCH_OFFER_START, FETCH_OFFER_SUCCESS, FETCH_OFFER_FAIL } from '../actions/types';

const initialState = {
	loading: false,
	success: null
};

export default (state = initialState, action) => {
	switch (action.type) {
		case FETCH_OFFER_START:
			return {
				...state,
				loading: true
			}
		case FETCH_OFFER_SUCCESS:
			return {
				...state,
				loading: false,
				success: action.payload.success,
				url: action.payload.offer.url
			}
		case FETCH_OFFER_FAIL:
			return {
				...state,
				loading: false
			}
		default:
			return state;
	}
}