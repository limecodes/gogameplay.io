import { FETCH_OFFER_START, FETCH_OFFER_SUCCESS, FETCH_OFFER_FAIL } from '../actions/types';

const initialState = {
	loading: false
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
				url: action.payload.url,
				success: action.payload.success
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