import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import { fetchOffer } from '../actions/offer';

class Searching extends Component {

	constructor(props) {
		super(props);

		this.state = {
			searching: true
		}
	}

	componentDidMount() {
		var self = this;

		//This will actually run after the fake set timeouts
		if (this.props.visitor.uid) {
			this.props.fetchOffer(this.props.visitor.uid);
		}

		setTimeout(function() {
			self.setState({
				searching: false
			});
		}, 1000);
	}

	componentDidUpdate(prevProps) {
		if (this.props.visitor.uid !== prevProps.visitor.uid) {
			this.props.fetchOffer(this.props.visitor.uid);
		}
	}

	render() {
		const Progress = () => {
			if (this.state.searching) {
				return (
					<div className="progress">
						<div className="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style={{ width: '100%'}}></div>
					</div>
				);
			} else {
				return (<div></div>);
			}
		};

		return (
			<div className="card" style={{ marginTop: '1rem' }}>
				<div className="card-body">
					{ (!this.state.searching) ? <div><span className="badge badge-success"><FontAwesomeIcon icon='check' /></span>{' '}Coupon Found</div> : <div>Searching</div> }
					<Progress />
					{ ( (this.props.offer.url) && (!this.state.searching) ) ? <a href={ this.props.offer.url } class="btn btn-success">Redeem Coupon</a> : <div></div> }
				</div>
			</div>
		);
	}

}

const mapStateToProps = state => ({
	visitor: state.visitor,
	offer: state.offer
});

export default connect(mapStateToProps, { fetchOffer })(Searching);
