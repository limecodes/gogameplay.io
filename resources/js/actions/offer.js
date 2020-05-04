import { FETCH_OFFER_START, FETCH_OFFER_SUCCESS, FETCH_OFFER_FAIL } from './types';

import axios from 'axios';

export const fetchOffer = (uid) => async dispatch => {
	dispatch({
		type: FETCH_OFFER_START
	});

	try {
		const response = await axios.post('/api/offers/fetch', {
			'uid': uid
		});

		const payload = response.data;

		dispatch({
			type: FETCH_OFFER_SUCCESS,
			payload: payload
		});
	} catch (error) {
		dispatch({
			type: FETCH_OFFER_FAIL
		});
	}
}